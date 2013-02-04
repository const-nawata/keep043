/**
Params:
slfObj - var name which present createSlideShow object

set=>array(
              id      - container id
              width   - container width in pixels
              height  - container height in pixels
              layout  - 'v' - vertical, 'h' - horizontal layout
              dir     - direction. 1 - from top to bottom or from left to right.
                                   0 - from bottom to top or from right to left.
              speed   - movie speed in pixels per second
              space   - space beween images in pixels
                )


imgFiles=>array(
              name
              width
              height
               )
*/

function createSlideShow(slfObj, set, imgFiles){
//    var n_test = 0;

    var self = this;
    this.isGoOn = true;
    this.cntObj    = document.getElementById(set['id']);
    this.qntImgs   = imgFiles.length;
    this.imgFiles;
    normArrays();


    this.cntSize   = getCntSize();
    this.firstImg  = 0;
    this.firstInd  = 0;
    this.builtSize = 0;
    this.snapShotSize = 0;

    this.max_rate	= 10;	//IMPORTANT:	Define max rate from computer performance. This value was set for my work computer.
    						//		It is 20 for my home computer. This ussue must be investigated. And it effects the
    						//		general behaviour of application.
    						//		Maybe it effects to Ctrl-F5 behavior of firefox.

    this.rate = (set['speed'] > this.max_rate) ? this.max_rate : set['speed'];
    this.delay = Math.round(1000/this.rate);
    this.scrollAdd = set['speed']/this.rate;  //  This value is not integer

    this.scrlPos   = 0;  //  This value is not integer
    this.itemInds;
    this.isNewSnapShot = true;

    this.tmOut	= null;

//  Private methods ********************************************
    function normArrays(){
       var i_fl;
       self.imgFiles = new Array();
       for (i_fl = 0; i_fl < self.qntImgs; i_fl++){
           self.imgFiles[i_fl] = {'name':imgFiles[i_fl]['name'], 'width':parseInt(imgFiles[i_fl]['width'], 10), 'height':parseInt(imgFiles[i_fl]['height'], 10)};
       }
    }
//-------------------------------------------------------------------------------------------------------------------------------

    function getSnapShot(){
        self.itemInds = getItemInds();
        setNewScrlPos();

        var qnt_items = self.itemInds.length;
        var htmlStr = "";
        var item_num;
        var html_item;

        htmlStr += "<table border='0' cellpadding='0' cellspacing='0'>";
        switch (set['layout']){
            case 'v':
                for (item_num = 0; item_num < qnt_items; item_num++){
                    html_item = getHtmlItem(item_num);
                    htmlStr += '<tr>'+html_item+'</tr>';
                    htmlStr += '<tr><td><div class="vSpace" style="height: '+set['space']+'px;">&nbsp;</div></td></tr>';
                }
            break;

            case 'h':
                htmlStr += "<tr>";
                for (item_num = 0; item_num < qnt_items; item_num++){
                    html_item = getHtmlItem(item_num);
                    htmlStr += html_item;
                    htmlStr += '<td><div class="gSpace" style="width: '+set['space']+'px;">&nbsp;</div></td>';
                }
                htmlStr += "</tr>";
            break;
        }
        htmlStr += "</table>";

        return htmlStr;
    }
//-------------------------------------------------------------------------------------------------------------------------------

    function getHtmlItem(itemInd){
        var htmlStr;
        htmlStr = '<td><div style="background: url(\''+set['path']+self.imgFiles[self.itemInds[itemInd]]['name']+'\'); width: '+self.imgFiles[self.itemInds[itemInd]]['width']+'px; height: '+self.imgFiles[self.itemInds[itemInd]]['height']+'px;">&nbsp;</div></td>';
        return htmlStr;
    }
//-------------------------------------------------------------------------------------------------------------------------------

    function getItemInds(){
        var newInd = self.firstInd;
        self.snapShotSize = 0;
        var snap_shot_inds = new Array();
        var item_ind = 0;

        while (newInd >= 0){
            snap_shot_inds[item_ind] = newInd;
            item_ind++;
            newInd = getNextImgInd(newInd);
        }

        if (set['dir'] == 1){
            snap_shot_inds.reverse();
        }

        return snap_shot_inds;
    }
//-------------------------------------------------------------------------------------------------------------------------------

    function getNextImgInd(currInd){
        var ind = currInd;
        var max_snap_shot_size = self.cntSize + getItemSize(self.firstInd)+ set['space'];
        self.snapShotSize += getItemSize(ind);
        if (self.snapShotSize < max_snap_shot_size){
            ind++;
            (ind == self.qntImgs) ? ind = 0 : ind;
        }else{
            ind = -1;
        }
        return ind;
    }
//-------------------------------------------------------------------------------------------------------------------------------

    function setNewScrlPos(){
        self.scrlPos += self.scrollAdd;
        var maxScrlVal = getItemSize(self.firstInd);
        if (self.scrlPos >= maxScrlVal){
            self.scrlPos -= maxScrlVal;
            self.firstInd++;
            (self.firstInd == self.qntImgs) ? self.firstInd = 0 : self.firstInd;
        }
    }
//-------------------------------------------------------------------------------------------------------------------------------

    function getItemSize(ind){
        var item_size;
        switch (set['layout']){
            case 'v':
                item_size = self.imgFiles[ind]['height'];
            break;

            case 'h':
                item_size = self.imgFiles[ind]['width'];
            break;

            default: item_size = 0;
        }
        item_size += set['space'];
        return item_size;
    }
//-------------------------------------------------------------------------------------------------------------------------------

    function getCntSize(){
        switch (set['layout']){
            case 'v':
                return set['height'];
            break;

            case 'h':
                return set['width'];
            break;

            default: return 0;
        }
    }
//-------------------------------------------------------------------------------------------------------------------------------

    function initContainer(){
        self.cntObj.style.height = set['height']+"px";
        self.cntObj.style.width = set['width']+"px";
        getItemInds();  //  This operation is necessary to calculate snapShotSize which is used for reversive movement
    }
//-------------------------------------------------------------------------------------------------------------------------------

    this.showSlides = function(){
    	var scroll_pos;
    	clearTimeout (this.tmOut);
	    if (this.isGoOn){

	        switch (set['dir']){
	            case 0: scroll_pos = Math.round(this.scrlPos); break;
	            case 1: scroll_pos = Math.round(this.snapShotSize - this.scrlPos - this.cntSize); break;
	            default: scroll_pos=0;
	        }

	        switch (set['layout']){
	            case 'v': this.cntObj.scrollTop = scroll_pos; break;
	            case 'h': this.cntObj.scrollLeft = scroll_pos; break;
	        }

	        if (this.isNewSnapShot)this.cntObj.innerHTML = getSnapShot();
	        else getSnapShot();



	        this.tmOut = setTimeout(slfObj+'.showSlides();', this.delay);

	    }else{
	    	this.showSlides = null;
	    	self = null;
	    }
    };


//Executive code ################################################
    initContainer();
    this.tmOut = setTimeout(slfObj+'.showSlides();', this.delay);
}
