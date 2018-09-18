  //public variable 
  var DefaultImg = ""; var imagedisplay=false; var savesession=false;var multi_crop = ''; var ratio=1;
  //function CropAvatar($element,$DefaultImg,$imagedisplay,$Ratio,$savesession) {
    function CropAvatar($json) {

    multi_crop = $json.multiple_upload; 
    /*NULL in case of one crop avatar on single page, not NULL in case of multiple crop avatar on same page with different number ids like crop-avatar-1, crop-avatar-2..*/
    
    this.$container = $json.element//$element;//$(document)
    this.$avatarView = $(document).find('.avatar-view'+multi_crop);
    this.$avatar = this.$avatarView.find('img');
    this.$avatarModal = this.$container.find('#avatar-modal');
    this.$loading     = $('#loading-image');//this.$container.find('.loading');

    this.$avatarForm = this.$avatarModal.find('.avatar-form');
    this.$avatarUpload = this.$avatarForm.find('.avatar-upload');
    this.$avatarSrc = this.$avatarForm.find('.avatar-src');
    this.$avatarData = this.$avatarForm.find('.avatar-data');
    this.$avatarInput = this.$avatarForm.find('.avatar-input');
    this.$avatarSave = this.$avatarForm.find('.avatar-save');
    this.$avatarBtns = this.$avatarForm.find('.avatar-btns');

    this.$avatarWrapper = this.$avatarModal.find('.avatar-wrapper');
    this.$avatarPreview = this.$avatarModal.find('.avatar-preview');
    this.$ratio         = ($json.ratio!="")?$json.ratio:ratio; //multiple images upload on single page
    this.$multi_crop    = multi_crop;//multiple images upload on single page
    this.$imagedisplay  = $json.imagedisplay;//display image

    DefaultImg    = ($json.defaultimg!="")?$json.defaultimg:DefaultImg;
    imagedisplay  = ($json.imagedisplay!="")?$json.imagedisplay:imagedisplay;
    savesession   = ($json.savesession!="")?$json.savesession:savesession;
    ratio         = ($json.ratio!="")?$json.ratio:ratio;
    this.init();
  //  console.log(this);
  }

  CropAvatar.prototype = {
    constructor: CropAvatar,

    support: {
      fileList: !!$('<input type="file">').prop('files'),
      blobURLs: !!window.URL && URL.createObjectURL,
      formData: !!window.FormData
    },

    init: function () {
      this.support.datauri = this.support.fileList && this.support.blobURLs;

      if (!this.support.formData) {
        this.initIframe();
      }

      this.initTooltip();
      this.initModal();
      this.addListener();
    },

    addListener: function () {
      this.$avatarView.on('click', $.proxy(this.click, this));
      this.$avatarInput.on('change', $.proxy(this.change, this));
      this.$avatarForm.on('submit', $.proxy(this.submit, this));
      this.$avatarBtns.on('click', $.proxy(this.rotate, this));
    },

    initTooltip: function () {
      this.$avatarView.tooltip({
        placement: 'bottom'
      });
    },

    initModal: function () {
      this.$avatarModal.modal({
        show: false
      });
    },

    initPreview: function () {
      var url = this.$avatar.attr('src');//preview area
      if(DefaultImg!=""){
        url = IMAGE_PATH+DefaultImg;
      }
      this.$avatarPreview.empty().html('<img src="' + url + '">');
    },

    initIframe: function () {
      var target = 'upload-iframe-' + (new Date()).getTime(),
          $iframe = $('<iframe>').attr({
            name: target,
            src: ''
          }),
          _this = this;

      // Ready ifrmae
      $iframe.one('load', function () {

        // respond response
        $iframe.on('load', function () {
          var data;

          try {
            data = $(this).contents().find('body').text();
          } catch (e) {
            console.log(e.message);
          }

          if (data) {
            try {
              data = $.parseJSON(data);
            } catch (e) {
              console.log(e.message);
            }

            _this.submitDone(data);
          } else {
            _this.submitFail('Image upload failed!');
          }

          _this.submitEnd();

        });
      });

      this.$iframe = $iframe;
      this.$avatarForm.attr('target', target).after($iframe.hide());
    },

    click: function () {
      this.$avatarModal.modal('show');
      this.initPreview();
    },

    change: function () {
      var files,
          file;

      if (this.support.datauri) {
        files = this.$avatarInput.prop('files');

        if (files.length > 0) {
          file = files[0];

          if (this.isImageFile(file)) {
            if (this.url) {
                URL.revokeObjectURL(this.url); // Revoke the old one
            }
            this.url = URL.createObjectURL(file);
            this.startCropper();
          }
        }
      } else {
        file = this.$avatarInput.val();

        if (this.isImageFile(file)) {
          this.syncUpload();
        }
      }
    },

    submit: function () {
      if (!this.$avatarSrc.val() && !this.$avatarInput.val()) {
        return false;
      }

      if (this.support.formData) {
        this.ajaxUpload();
        return false;
      }
    },

    rotate: function (e) {
      var data;

      if (this.active) {
        data = $(e.target).data();

        if (data.method) {
          this.$img.cropper(data.method, data.option);
        }
      }
    },

    isImageFile: function (file) {
      if (file.type) {
        return /^image\/\w+$/.test(file.type);
      } else {
        return /\.(jpg|jpeg|png|gif)$/.test(file);
      }
    },

    startCropper: function () {
      var _this = this;
      if (this.active) {
        this.$img.cropper('replace', this.url);
      } else {
        if (this.$avatarUpload.hasClass('avater-alert')){
         // it has class
         $('.avater-alert').remove();
      } 
        this.$img = $('<img src="' + this.url + '">');
        this.$avatarWrapper.empty().html(this.$img);
        this.$img.cropper({
          aspectRatio: this.$ratio,
          preview: this.$avatarPreview.selector,
          strict: true,
          crop: function (data) {
            var json = [
                  '{"x":' + data.x,
                  '"y":' + data.y,
                  '"height":' + data.height,
                  '"width":' + data.width,
                  '"rotate":' + data.rotate + '}'
                ].join();

            _this.$avatarData.val(json);
          }
        });

        this.active = true;
      }
    },

    stopCropper: function () {
      if (this.active) {
        this.$img.cropper('destroy');
        this.$img.remove();
        this.active = false;
      }
    },

    ajaxUpload: function () {
      var url = this.$avatarForm.attr('action'),
          data = new FormData(this.$avatarForm[0]),
          _this = this;

      $.ajax(url, {
        type: 'post',
        data: data,
        dataType: 'json',
        processData: false,
        contentType: false,

        beforeSend: function () {
          _this.submitStart();
        },

        success: function (data) {
          _this.submitDone(data);
        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {
          _this.submitFail(textStatus || errorThrown);
        },

        complete: function () {
          _this.submitEnd();
        }
      });
    },

    syncUpload: function () {
      this.$avatarSave.click();
    },

    submitStart: function () {
      this.$loading.fadeIn();
    },

    submitDone: function (data) {
      /*if(savesession)
      {
        saveimg(data['result']);
      }*/  

    
      if ($.isPlainObject(data) && data.state === 200) {
        if (data.result) {
          this.url = IMAGE_PATH + data.result;

          if (this.support.datauri || this.uploaded) {
            this.uploaded = false;
            this.cropDone();
          } else {
            this.uploaded = true;
            this.$avatarSrc.val(this.url);
            this.startCropper();
          }

          this.$avatarInput.val('');
        } else if (data.message) {
          this.alert(data.message);
        }
      } else {
        this.alert('Failed to response');
      }
    },

    submitFail: function (msg) {
      this.alert(msg);
    },

    submitEnd: function () {
      this.$loading.fadeOut();
    },

    cropDone: function () {
      this.$avatarForm.get(0).reset();
      this.$avatar.attr('src', '');
      //image display
      if(this.$imagedisplay){
        this.$avatar.attr('src', this.url);
        $('.class_hidden_image').val(onlyfilename);
        this.stopCropper();
      }
      var onlyfilename = this.url.replace(/^.*[\\\/]/, '');
        
      this.$avatarModal.modal('hide');
      if(this.$multi_crop!=''){
        /*if multiple images are uplaoded in same view pass 3 arguments, 
        URL of the image uploaded
        NAME of the image uploaded
        NUMBER of image so that it can be known which image is uploaded as there are multiple images on the same page*/
        if(this.$imagedisplay!=''){
          saveimage( this.url,onlyfilename,this.$multi_crop,this.$imagedisplay); 
        }else{
          saveimage( this.url,onlyfilename,this.$multi_crop); 
        }

      }else{
        saveimage( this.url,onlyfilename);
      }
    },

    alert: function (msg) {
      if(msg=='parsererror'){
         msg = 'Please crop image properly';
      }
      //if (this.$avatarUpload.hasClass('avater-alert')){
         // it has class
         $('.avater-alert').remove();
      //} 
        var $alert = [
            '<div class="alert alert-danger avater-alert">',
              '<button type="button" class="close" data-dismiss="alert">&times;</button>',
              msg,
            '</div>'
          ].join('');
      

      this.$avatarUpload.after($alert);
      setTimeout(function() {
         $('.avater-alert').delay(2000).fadeOut();
        }, 500 );
    }
  };

  



function loadcroppedimages()
{
    $.ajax({
        url: BASEURL+"/admin/products/loadcroppedimage",
        success:function(data){
            $("#img-section .row #current_session").html(data);
         }     
      });
}

function saveimg(img)
{
   $.ajax({
      url: BASEURL+"/admin/products/save_img_session",
      type: "POST",
      data:  {'img':img},
      success: function(data)
      {
        loadcroppedimages();
      }
  });
}
