var mergeTags = [];

var beeConfig = {
    uid: '55hubs-template',
    container: 'bee-plugin-container',
    autosave: false,
    language: 'en-US',
    mergeTags: [],
    onSave: function (jsonFile, htmlFile) {
        mQuery('#emailform_description').val(jsonFile);
        mQuery('.builder-html').val(htmlFile);
        mQuery('#bee-plugin-container').hide();
        mQuery('#emailform_buttons_beebuilder_toolbar').children().removeClass('fa-spin fa-spinner')
    },
    onSaveAsTemplate: function (jsonFile) { // + thumbnail? 
        save('newsletter-template.json', jsonFile);
    },
    onAutoSave: function (jsonFile) { // + thumbnail? 
        console.log(new Date().toISOString() + ' autosaving...');
        window.localStorage.setItem('newsletter.autosave', jsonFile);
    },
    onSend: function (htmlFile) {
        //write your send test function here
    },
    onError: function (errorMessage) {
        console.log('onError ', errorMessage);
    },
    tokenData: {}
};
    Mautic.getTokens('email:getBuilderTokens', function(tokens) {
        mQuery.each(tokens, function(i, j){
            beeConfig.mergeTags.push({'name' : j, 'value' : i})
        });
    });
var bee = null;

Mautic.launchBeeBuilder = function () {
    if(typeof BEE_TOKEN === 'undefined'){
        return; 
    }
    beeConfig.uid = BEE_UID;
    beeConfig.language = BEE_LOCALE;
    if(mQuery.isEmptyObject(beeConfig.tokenData)){
        beeConfig.tokenData = BEE_TOKEN;
    }
    var panelHeight = (mQuery('.builder-content').css('right') == '0px') ? mQuery('.builder-panel').height() : 0,
        panelWidth = (mQuery('.builder-content').css('right') == '0px') ? 0 : mQuery('.builder-panel').width(),
        spinnerLeft = (mQuery(window).width() - panelWidth - 60) / 2,
        spinnerTop = (mQuery(window).height() - panelHeight - 60) / 2;
    mQuery('<div id="builder-overlay" class="modal-backdrop fade in"><div style="position: absolute; top:' + spinnerTop + 'px; left:' + spinnerLeft + 'px" class="builder-spinner"><i class="fa fa-spinner fa-spin fa-5x"></i></div></div>')
            .appendTo('#bee-plugin-container');
    mQuery('#bee-plugin-container').show();
    mQuery('#emailform_template').val('mautic_code_mode');
    var request = function(method, url, data, type, callback) {
        var req = new XMLHttpRequest();
        req.onreadystatechange = function() {
            if (req.readyState === 4 && req.status === 200) {
                var response = JSON.parse(req.responseText);
                callback(response);
            }
        };

        req.open(method, url, true);
        if (data && type) {
            if(type === 'multipart/form-data') {
                var formData = new FormData();
                for (var key in data) {
                  formData.append(key, data[key]);
                }
                data = formData;          
            }
            else {
              req.setRequestHeader('Content-type', type);
            }
        }

        req.send(data);
    };
    
    var initBeeeditor = function(token){
        mQuery('#builder-overlay').remove();
        var templatejson = mQuery('#bee-plugin-container').data('template');
        BeePlugin.create(token, beeConfig, function(beePluginInstance) {
            bee = beePluginInstance;
            if(templatejson){
                bee.start(templatejson);
            }else{
              request(
                'GET', 
                'https://rsrc.getbee.io/api/templates/m-bee',
                null,
                null,
                function(template) {
                      bee.start(template);
                });
            }
        });
    };
    
    var generateToken = function(){
        mQuery.ajax({
            url: mauticBaseUrl+'s/generatebeetoken',
            dataType: "json",
            success: function (response) {
                if (typeof response.tokens !== 'undefined') {
                    // store the tokens to the session storage
                    beeConfig.tokenData = response.tokens;
                    initBeeeditor(beeConfig.tokenData);
                }
            },
            error: function (request, textStatus, errorThrown) {
                mQuery('#bee-plugin-container').hide();
            }
        });
    };
    
    if((new Date(beeConfig.tokenData[".expires"]).getTime()/1000) - Math.floor(Date.now() / 1000) <= 0){
        generateToken();
    }else{
        initBeeeditor(beeConfig.tokenData);
    }
    
    var initCancel = function(){
        mQuery('<div style="position: absolute;top: 1px;right: -2px;"><a id="beditor_cancel_btn" class="btn"><i class="fa fa-times-circle fs-24 text-white"></i></a></div>')
        .prependTo('#bee-plugin-container');
        
        mQuery('#beditor_cancel_btn').on('click',function(){         
            mQuery('#bee-plugin-container').hide();
            mQuery('#emailform_buttons_beebuilder_toolbar').children().removeClass('fa-spin fa-spinner').addClass('fa fa-cube');
            mQuery('#bee-plugin-container').empty();
        });
    }();
}