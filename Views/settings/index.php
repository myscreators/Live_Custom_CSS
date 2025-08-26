<style>
    #css-editor {
        width: 100%;
        height: 400px;
        border-radius: 6px;
        border: 1px solid rgba(148,163,184,0.25);
    }
</style>

<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "live_custom_css";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="card">
                <div class="page-title clearfix">
                    <h4 class="mt15"><?php echo app_lang('live_custom_css'); ?></h4>
                </div>
                <div class="p20">
                    <?php echo form_open(get_uri("live_custom_css_settings/save"), array("id" => "custom-css-form", "class" => "general-form", "role" => "form")); ?>
                    <div class="form-group">
                        <label for="css-editor"><?php echo app_lang('css_code'); ?></label>
                        <div id="css-editor"></div>
                        <textarea id="custom_css" name="custom_css" style="display:none;"><?php echo htmlspecialchars($custom_css ?? ''); ?></textarea>
                    </div>
                    <div class="form-group mt20 d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-primary">
                            <span data-feather="check-circle" class="icon-16"></span> 
                            <?php echo app_lang("save"); ?>
                        </button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.22.1/ace.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.22.1/mode-css.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.22.1/theme-twilight.js"></script>

<script type="text/javascript">
$(document).ready(function () {
    var editor = ace.edit("css-editor");
    editor.session.setMode("ace/mode/css");
    editor.setTheme("ace/theme/twilight");
    editor.setOptions({
        fontSize: "14px",
        showPrintMargin: false,
        wrap: true
    });
    
    editor.setValue($("#custom_css").val() || "", -1);

    $("#custom-css-form").submit(function(e) {
        e.preventDefault();
        
        $("#custom_css").val(editor.getValue());
        
        var $form = $(this);
        var $button = $form.find('button[type=submit]');
        
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            cache: false,
            beforeSend: function() {
                $button.prop('disabled', true)
                       .find('span')
                       .removeClass('icon-16')
                       .addClass('fa fa-spinner fa-spin');
            },
            success: function(response) {
                if (response && response.success) {
                    appAlert.success(response.message || appLang('settings_updated'), {duration: 5000});
                } else {
                    appAlert.error((response && response.message) ? response.message : 'Error al guardar el CSS');
                }
            },
            error: function(xhr, status, error) {
                appAlert.error('Error de conexión: ' + (xhr.responseText || error));
            },
            complete: function() {
                $button.prop('disabled', false)
                       .find('span')
                       .removeClass('fa fa-spinner fa-spin')
                       .addClass('icon-16');
            }
        });
    });
});
</script>
