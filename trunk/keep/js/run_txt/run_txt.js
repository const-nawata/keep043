function runningTextPx(slfObj, cntId, cntWidth, content, speed, delimiter){
    var self = this;
    this.isGoOn = true;
    (delimiter == undefined) ? this.content = content + " " : this.content = content + delimiter;
    this.cntObj = document.getElementById(cntId);
    this.position = 0;
    this.delay = Math.round(1000 / speed);
    (this.delay < 20 ) ? this.delay = 20 :'';

    this.scrollAdd = speed/(1000/this.delay);  //  This value is not integer

    setSysMode();
    this.contentWidth = getTextWidth(this.content);
    this.scrollText = getScrollText();
    this.scrlPos   = 0;  //  This value is not integer

    this.tmOut	= null;

//  Private methods ##########################################################
    function setSysMode(){
        self.cntObj.style.visibility = 'hidden';
        self.cntObj.style.whiteSpace = 'nowrap';
        self.cntObj.style.overflow = 'hidden';
        self.cntObj.style.width = '1px';
    }
//______________________________________________________________________________

    function setCustomMode(){
        self.cntObj.style.visibility = 'visible';
        self.cntObj.style.width = cntWidth + 'px';
        self.cntObj.innerHTML = self.scrollText;
    }
//______________________________________________________________________________

    function getTextWidth(txt){
        self.cntObj.innerHTML = txt + 'W';
        var w1 = self.cntObj.scrollWidth;
        self.cntObj.innerHTML = txt + 'WW';
        var w2 = self.cntObj.scrollWidth;
        return w1 * 2 - w2;
    }
//______________________________________________________________________________

    function getScrollText(){
        var msg = self.content + self.content;
        //var msg = '';
        var msg_length = getTextWidth(msg);
        while ((cntWidth * 2) > msg_length){
            msg += self.content;
            msg_length = getTextWidth(msg);
        }
        return msg;
    }
//______________________________________________________________________________

     this.ticker = function(){
    	 clearTimeout (this.tmOut);
      if (this.isGoOn){
          var scroll_pos = Math.round(this.scrlPos);
          this.cntObj.scrollLeft = scroll_pos;

          this.scrlPos += this.scrollAdd;
          (this.scrlPos > this.contentWidth) ? this.scrlPos = this.scrollAdd * 3 :'';

          this.tmOut = setTimeout(slfObj + ".ticker()", this.delay);
      }else{this.ticker = null; self = null;}
     };

     setCustomMode();
     this.ticker();
}
