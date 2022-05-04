/*
 * formatter.js
 *
 * Class used to format input based on passed pattern
 *
 */

/* ExcludeStart */
var pattern = require('./pattern'),
    inptSel = require('./inpt-sel'),
    utils   = require('./utils');

// Define module
module.exports = Formatter;
/* ExcludeEnd */

// Defaults
var defaults = {
  persistent: false,
  repeat: false,
  placeholder: ' '
};

// Regexs for input validation
var inptRegs = {
  '9': /[0-9]/,
  'a': /[A-Za-z]/,
  '*': /[A-Za-z0-9]/
};

//
// Class Constructor - Called with new Formatter(el, opts)
// Responsible for setting up required instance variables, and
// attaching the event listener to the element.
//
function Formatter(el, opts) {
  // Cache this
  var self = this;

  // Make sure we have an element. Make accesible to instance
  self.el = el;
  if (!self.el) { 
    throw new TypeError('Must provide an existing element');
  }

  // Merge opts with defaults
  self.opts = utils.extend({}, defaults, opts);

  // Make sure we have valid opts
  if (typeof self.opts.pattern === 'undefined') {
    throw new TypeError('Must provide a pattern');
  }

  // Get info about the given pattern
  var parsed   = pattern.parse(self.opts.pattern);
  self.mLength = parsed.mLength;
  self.chars   = parsed.chars;
  self.inpts   = parsed.inpts;

  // Init values
  self.hldrs = {};
  self.focus = 0;

  // Add Listeners
  utils.addListener(self.el, 'keydown', function (evt) {
    self._keyDown(evt);
  });
  utils.addListener(self.el, 'keypress', function (evt) {
    self._keyPress(evt);
  });
  utils.addListener(self.el, 'paste', function (evt) {
    self._paste(evt);
  });

  // Persistence
  if (self.opts.persistent) {
    // Format on start
    self._processKey('', false);
    self.el.blur();

    // Add Listeners
    utils.addListener(self.el, 'focus', function (evt) {
      self._focus(evt);
    });
    utils.addListener(self.el, 'click', function (evt) {
      self._focus(evt);
    });
    utils.addListener(self.el, 'touchstart', function (evt) {
      self._focus(evt);
    });
  }
}

//
// @public
// Add new char
//
Formatter.addInptType = function (char, reg) {
  inptRegs[char] = reg;
};

//
// @public
// Handler called on all keyDown strokes. All keys trigger
// this handler. Only process delete keys.
//
Formatter.prototype.resetPattern = function (str) {
  // Update opts to hold new pattern
  this.opts.pattern = str;
  
  // Get current state
  this.sel = inptSel.get(this.el);
  this.val = this.el.value;

  // Init values
  this.delta = 0;

  // Remove all formatted chars from val
  this._removeChars();

  // Update pattern
  var parsed   = pattern.parse(str);
  this.mLength = parsed.mLength;
  this.chars   = parsed.chars;
  this.inpts   = parsed.inpts;

  // Format on start
  this._processKey('', false);
};

//
// @private
// Handler called on all keyDown strokes. All keys trigger
// this handler. Only process delete keys.
//
Formatter.prototype._keyDown = function (evt) {
  // The first thing we need is the character code
  var k = evt.which || evt.keyCode;

  // If delete key
  if (k && utils.isDelKey(k)) {
    // Process the keyCode and prevent default
    this._processKey(null, k);
    return utils.preventDefault(evt);
  }
};

//
// @private
// Handler called on all keyPress strokes. Only processes
// character keys (as long as no modifier key is in use).
//
Formatter.prototype._keyPress = function (evt) {
  // The first thing we need is the character code
  var k, isSpecial;
  // Mozilla will trigger on special keys and assign the the value 0
  // We want to use that 0 rather than the keyCode it assigns.
  if (evt.which) {
    k = evt.which;
  } else {
    k = evt.keyCode;
    isSpecial = utils.isSpecialKey(k);
  }
  // Process the keyCode and prevent default
  if (!utils.isDelKey(k) && !isSpecial && !utils.isModifier(evt)) {
    this._processKey(String.fromCharCode(k), false);
    return utils.preventDefault(evt);
  }
};

//
// @private
// Handler called on paste event.
//
Formatter.prototype._paste = function (evt) {
  // Process the clipboard paste and prevent default
  this._processKey(utils.getClip(evt), false);
  return utils.preventDefault(evt);
};

//
// @private
// Handle called on focus event.
//
Formatter.prototype._focus = function (evt) {
  // Wrapped in timeout so that we can grab input selection
  var self = this;
  setTimeout(function () {
    // Grab selection
    var selection = inptSel.get(self.el);
    // Char check
    var isAfterStart = selection.end > self.focus;
        isFirstChar  = selection.end === 0;
    // If clicked in front of start, refocus to start
    if (isAfterStart || isFirstChar) {
      inptSel.set(self.el, self.focus);
    }
  }, 0);
};

//
// @private
// Using the provided key information, alter el value.
//
Formatter.prototype._processKey = function (chars, delKey) {
  // Get current state
  this.sel = inptSel.get(this.el);
  this.val = this.el.value;

  // Init values
  this.delta = 0;

  // If chars were highlighted, we need to remove them
  if (this.sel.begin !== this.sel.end) {
    this.delta = (-1) * Math.abs(this.sel.begin - this.sel.end);
    this.val   = utils.removeChars(this.val, this.sel.begin, this.sel.end);
  }

  // Delete key (moves opposite direction)
  else if (delKey && delKey == 46) {
    this._delete();

  // or Backspace and not at start
  } else if (delKey && this.sel.begin - 1 >= 0) {
    this.val = utils.removeChars(this.val, this.sel.end -1, this.sel.end);
    this.delta = -1;

  // or Backspace and at start - exit
  } else if (delKey) {
    return true;
  }

  // If the key is not a del key, it should convert to a str
  if (!delKey) {
    // Add char at position and increment delta
    this.val = utils.addChars(this.val, chars, this.sel.begin);
    this.delta += chars.length;
  }

  // Format el.value (also handles updating caret position)
  this._formatValue();
};

//
// @private
// Deletes the character in front of it
//
Formatter.prototype._delete = function () {
  // Adjust focus to make sure its not on a formatted char
  while (this.chars[this.sel.begin]) {
    this._nextPos();
  }

  // As long as we are not at the end
  if (this.sel.begin < this.val.length) {
    // We will simulate a delete by moving the caret to the next char
    // and then deleting
    this._nextPos();
    this.val = utils.removeChars(this.val, this.sel.end -1, this.sel.end);
    this.delta = -1;
  }
};

//
// @private
// Quick helper method to move the caret to the next pos
//
Formatter.prototype._nextPos = function () {
  this.sel.end ++;
  this.sel.begin ++;
};

//
// @private
// Alter element value to display characters matching the provided
// instance pattern. Also responsible for updatin
//
Formatter.prototype._formatValue = function () {
  // Set caret pos
  this.newPos = this.sel.end + this.delta;

  // Remove all formatted chars from val
  this._removeChars();
  // Validate inpts
  this._validateInpts();
  // Add formatted characters
  this._addChars();

  // Set vakye and adhere to maxLength 
  this.el.value = this.val.substr(0, this.mLength);

  // Set new caret position
  inptSel.set(this.el, this.newPos);
};

//
// @private
// Remove all formatted before and after a specified pos
//
Formatter.prototype._removeChars = function () {
  // Delta shouldn't include placeholders
  if (this.sel.end > this.focus) {
    this.delta += this.sel.end - this.focus;
  }

  // Account for shifts during removal
  var shift = 0;

  // Loop through all possible char positions
  for (var i = 0; i <= this.mLength; i++) {
    // Get transformed position
    var curChar = this.chars[i],
        curHldr = this.hldrs[i],
        pos = i + shift,
        val;

    // If after selection we need to account for delta
    pos = (i >= this.sel.begin) ? pos + this.delta : pos;
    val = this.val.charAt(pos);
    // Remove char and account for shift
    if (curChar && curChar == val || curHldr && curHldr == val) {
      this.val = utils.removeChars(this.val, pos, pos + 1);
      shift--;
    }
  }

  // All hldrs should be removed now
  this.hldrs = {};

  // Set focus to last character
  this.focus = this.val.length;
};

//
// @private
// Make sure all inpts are valid, else remove and update delta
//
Formatter.prototype._validateInpts = function () {
  // Loop over each char and validate
  for (var i = 0; i < this.val.length; i++) {
    // Get char inpt type
    var inptType = this.inpts[i];

    // Checks
    var isBadType = !inptRegs[inptType],
        isInvalid = !isBadType && !inptRegs[inptType].test(this.val.charAt(i)),
        inBounds  = this.inpts[i];

    // Remove if incorrect and inbounds
    if ((isBadType || isInvalid) && inBounds) {
      this.val = utils.removeChars(this.val, i, i + 1);
      this.focusStart--;
      this.newPos--;
      this.delta--;
      i--;
    }
  }
};

//
// @private
// Loop over val and add formatted chars as necessary
//
Formatter.prototype._addChars = function () {
  if (this.opts.persistent) { 
    // Loop over all possible characters
    for (var i = 0; i <= this.mLength; i++) {
      if (!this.val.charAt(i)) {
        // Add placeholder at pos
        this.val = utils.addChars(this.val, this.opts.placeholder, i);
        this.hldrs[i] = this.opts.placeholder;
      }
      this._addChar(i);
    }

    // Adjust focus to make sure its not on a formatted char
    while (this.chars[this.focus]) {
      this.focus++;
    }
  } else {
    // Avoid caching val.length, as it changes during manipulations
    for (var j = 0; j <= this.val.length; j++) {
      // When moving backwards there are some race conditions where we
      // dont want to add the character
      if (this.delta <= 0 && (j == this.focus)) { return true; }
      this._addChar(j);
    }
  }
};

//
// @private
// Add formattted char at position
//
Formatter.prototype._addChar = function (i) {
  // If char exists at position
  var char = this.chars[i];
  if (!char) { return true; }

  // If chars are added in between the old pos and new pos
  // we need to increment pos and delta
  if (utils.isBetween(i, [this.sel.begin -1, this.newPos +1])) {
    this.newPos ++;
    this.delta ++;
  }

  // If character added before focus, incr
  if (i <= this.focus) {
    this.focus++;
  }

  // Updateholder
  if (this.hldrs[i]) {
    delete this.hldrs[i];
    this.hldrs[i + 1] = this.opts.placeholder;
  }

  // Update value
  this.val = utils.addChars(this.val, char, i);
};
