var arCU = {
    prevOrder: null,
    ajaxUrl: null,
    addTitle: null,
    editTitle: null,
    successSaveMessage: null,
    successOrderMessage: null,
    successDeleteMessage: null,
    errorMessage: null,
    nonce: null,
    switchLang: function(lang){
        jQuery('.arcu-lang-content .arcu-lang-field').removeClass('active').addClass('hidden');
        jQuery('.arcu-lang-content .arcu-lang-field[data-lang-code="' + lang + '"]').addClass('active').removeClass('hidden');
        jQuery('.arcu-lang').each(function(){
            jQuery(this).find('.menu .item').removeClass('active').removeClass('selected');
            jQuery(this).find('.menu .item[data-lang-code="' + lang + '"]').addClass('active').addClass('selected');
            jQuery(this).find('.text').html(jQuery(this).find('.menu .item[data-lang-code="' + lang + '"]').html());
        });
    },
    activate: function(){
        arCU.blockUI('#arcontactus-plugin-container');
        jQuery('#arcontactus_activation_error').text('');
        jQuery.ajax({
            type: 'GET',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: {
                action : 'arcontactus_activate',
                _wpnonce: arCU.nonce,
                ajax : true,
                code: jQuery('#arcontactus_pcode').val()
            },
            success: function(data){
                arCU.unblockUI('#arcontactus-plugin-container');
                if (data.success == 0){
                    jQuery('#arcontactus_activation_error').text(data.error);
                }else{
                    alert('Plugin activated. Thank you. Page will be reloaded after you close this modal.');
                    location.reload();
                }
            }
        }).fail(function(){
            arCU.unblockUI('#arcontactus-plugin-container');
            arCU.showErrorMessage(arCU.errorMessage);
        });
    },
    deactivate: function(){
        arCU.blockUI('#arcontactus-plugin-container');
        jQuery('#arcontactus_activation_error').text('');
        jQuery.ajax({
            type: 'GET',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: {
                action : 'arcontactus_deactivate',
                _wpnonce: arCU.nonce,
                ajax : true
            },
            success: function(data){
                arCU.unblockUI('#arcontactus-plugin-container');
                if (data.success == 0){
                    jQuery('#arcontactus_activation_error').text(data.error);
                }else{
                    alert('Plugin deactivated. Page will be reloaded after you close this modal.');
                    location.reload();
                }
            }
        }).fail(function(){
            arCU.unblockUI('#arcontactus-plugin-container');
            arCU.showErrorMessage(arCU.errorMessage);
        });
    },
    comment: {
        edit: function(id){
            jQuery.ajax({
                type: 'GET',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_edit_comment',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    id: id
                },
                success: function(data){
                    jQuery('#arcu-comment-modal').uiModal({
                        closable: false
                    }).uiModal('show');
                    jQuery('#arcu-request-item-content').html(data.header);
                    jQuery('#arcu-comment_comment').val(data.model.comment);
                    id: jQuery('#arcu-comment_id').val(data.model.id);
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-prompt-modal');
                arCU.showErrorMessage(arCU.errorMessage);
            });
            
        },
        save: function(){
            var id = jQuery('#arcu-comment_id').val();
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_save_comment',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    comment: jQuery('#arcu-comment_comment').val(),
                    id: id
                },
                success: function(data){
                    jQuery('#arcu-comment-' + id).replaceWith(data.content);
                    jQuery('#arcu-comment-modal').uiModal('hide');
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-prompt-modal');
                arCU.showErrorMessage(arCU.errorMessage);
            });
            
        }
    },
    prompt: {
        add: function(){
            arCU.prompt.resetForm();
            jQuery('#arcontactus-prompt-modal-title').html(arCU.addTitle);
            jQuery('#arcontactus-prompt-modal').uiModal({
                closable: false
            }).uiModal('show');
        },
        populateForm: function(data){
            jQuery.each(data, function(i){
                var fieldId = '#arcontactus_prompt_' + i;
                if (typeof data[i] == 'object') {
                    jQuery.each(data[i], function(ii){
                        var fieldId = '#arcontactus_prompt_' + i + '_' + ii;
                        jQuery(fieldId).val(data[i][ii]);
                    });
                } else {
                    jQuery(fieldId).val(data[i]);
                }
            });
        },
        edit: function(id){
            arCU.prompt.resetForm();
            jQuery('#arcontactus-prompt-modal-title').html(arCU.editTitle);
            arCU.blockUI('#arcontactus-prompt-table');
            jQuery.ajax({
                type: 'GET',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_edit_prompt_item',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    id: id
                },
                success: function(data){
                    jQuery('#arcontactus-prompt-modal').uiModal({
                        closable: false
                    }).uiModal('show');
                    arCU.prompt.populateForm(data);
                    arCU.unblockUI('#arcontactus-prompt-table');
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-prompt-modal');
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        toggle: function(id){
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_switch_prompt_item',
                    _wpnonce: arCU.nonce,
                    id: id,
                    ajax : true
                },
                success: function(data){
                    arCU.prompt.reload();
                }
            }).fail(function(){
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        _getFormData: function(){
            var params = {};
            jQuery('#arcontactus-prompt-form [data-serializable="true"]').each(function(){
                if (jQuery(this).data('lang-field')) {
                    if (typeof params[jQuery(this).attr('name')] == 'undefined'){
                        params[jQuery(this).attr('name')] = {};
                    }
                    var lang = jQuery(this).data('lang-code');
                    params[jQuery(this).attr('name')][lang] = jQuery(this).val();
                } else {
                    params[jQuery(this).attr('name')] = jQuery(this).val();
                }
            });
            console.log(params);
            return params;
        },
        save: function(){
            var params = arCU.prompt._getFormData();
            arCU.blockUI('#arcontactus-prompt-modal');
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_save_prompt_item',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    data: params,
                    id: jQuery('#arcontactus_prompt_id').val()
                },
                success: function(data){
                    arCU.unblockUI('#arcontactus-prompt-modal');
                    if (!arCU.prompt.processErrors(data)){
                        arCU.showSuccessMessage(arCU.successSaveMessage);
                        jQuery('#arcontactus-prompt-modal').uiModal('hide');
                        arCU.prompt.reload();
                    }
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-prompt-modal');
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        clearErrors: function(){
            jQuery('#arcontactus-prompt-form .field.has-error').removeClass('has-error');
        },
        processErrors: function(data){
            arCU.prompt.clearErrors();
            if (data.success == 0){
                jQuery.each(data.errors, function(index){
                    jQuery('#arcontactus_prompt_'+index).parents('.field').addClass('has-error');
                    jQuery('#arcontactus_prompt_'+index).parents('.field').find('.errors').html(data.errors[index].join('<br/>'));
                });
                arCU.showErrorMessage(arCU.errorMessage);
                return true;
            }
            return false;
        },
        remove: function(id){
            if (!confirm('Delete this item?')){
                return false;
            }
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_delete_prompt_item',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    id: id
                },
                success: function(data){
                    arCU.showSuccessMessage(arCU.successDeleteMessage);
                    arCU.prompt.reload(true);
                }
            }).fail(function(){
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        updateOrder: function(table, silent){
            var positions = [];
            jQuery(table).find('tbody tr').each(function(index){
                var order = index + 1;
                var id = jQuery(this).data('id');
                positions.push(id + '_' + order);
            });
            arCU.blockUI(table);
            if (arCU.prevOrder != positions){
                jQuery.ajax({
                    type: 'POST',
                    url: arCU.ajaxUrl,
                    dataType: 'json',
                    data: {
                        action : 'arcontactus_reorder_prompt_items',
                        _wpnonce: arCU.nonce,
                        ajax : true,
                        data: positions
                    },
                    success: function(data){
                        arCU.unblockUI(table);
                        arCU.prevOrder = positions;
                        if (!silent){
                            //arCU.showSuccessMessage(arCU.successOrderMessage);
                        }
                        jQuery(table).find('tbody tr').each(function(index){
                            var order = index + 1;
                            jQuery(this).find('.position').text(order);
                        });
                    }
                }).fail(function(){
                    arCU.unblockUI(table);
                    arCU.showErrorMessage(arCU.errorMessage);
                });
            }
        },
        reload: function(reorder){
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    _ajax_nonce: arCU.nonce,
                    _wpnonce: arCU.nonce,
                    action : 'arcontactus_reload_prompt_items',
                    ajax : true,
                },
                success: function(data){
                    jQuery('#arcontactus-prompt-table').replaceWith(data.content);
                    arCU.init();
                    if (reorder){
                        arCU.prompt.updateOrder('#arcontactus-prompt-table', true);
                    }
                }
            }).fail(function(){
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        resetForm: function(){
            jQuery('#arcontactus-prompt-form [data-default]').each(function(){
                var attr = jQuery(this).attr('data-default');
                if (typeof attr !== typeof undefined && attr !== false) {
                    jQuery(this).val(jQuery(this).data('default'));
                }
            });
            arCU.prompt.clearErrors();

        },
    },
    callback: {
        updateCounter: function(){
            jQuery.ajax({
                type: 'GET',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_callback_count',
                    _wpnonce: arCU.nonce,
                    location: window.location.href,
                    ajax : true
                },
                success: function(data){
                    jQuery('#arcontactus-cb-count .update-count').text(data.count);
                    if (data.count == 0){
                        jQuery('#arcontactus-cb-count').hide();
                    }else{
                        jQuery('#arcontactus-cb-count').css({
                            display: 'inline'
                        });
                    }
                }
            }).fail(function(){
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        export: function(){
            arCU.blockUI('#arcontactus-requests-form');
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_export_callback',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    location: window.location.href
                },
                success: function(data){
                    arCU.unblockUI('#arcontactus-requests-form');
                    window.location = data.file;
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-requests-form');
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        reload: function(){
            arCU.blockUI('#arcontactus-requests-form');
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_reload_callback',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    location: window.location.href
                },
                success: function(data){
                    arCU.unblockUI('#arcontactus-requests-form');
                    jQuery('#arcontactus-requests-form').replaceWith(data.content);
                }
            }).fail(function(){
                arCU.unblockUI('#arcontactus-requests-form');
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        toggle: function(id, status){
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_switch_callback',
                    _wpnonce: arCU.nonce,
                    id: id,
                    status: status,
                    location: window.location.href,
                    ajax : true
                },
                success: function(data){
                    arCU.callback.reload();
                    arCU.callback.updateCounter();
                }
            }).fail(function(){
                arCU.showErrorMessage(arCU.errorMessage);
            });
        },
        remove: function(id){
            if (!confirm('Delete this item?')){
                return false;
            }
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                location: window.location.href,
                dataType: 'json',
                data: {
                    action : 'arcontactus_delete_callbacks',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    id: id
                },
                success: function(data){
                    arCU.showSuccessMessage(arCU.successDeleteMessage);
                    arCU.callback.reload();
                }
            }).fail(function(){
                arCU.showErrorMessage(arCU.errorMessage);
            });
        }
    },
    init: function(){
        jQuery("#arcontactus-prompt-table tbody").sortable({	
            axis: "y",
            handle: ".drag-handle",
            update: function(event, ui){
                arCU.prompt.updateOrder(jQuery("#arcontactus-prompt-table"), false);
            }
        });
        jQuery("#arcontactus-menu-items tbody").sortable({	
            axis: "y",
            handle: ".drag-handle",
            update: function(event, ui){
                arCU.updateOrder(jQuery("#arcontactus-menu-items"), false);
            }
        });
        jQuery('#arcontactus-modal').on('shown.bs.modal', function () {
            jQuery('#fa5-container').scrollTo(0);
            if (jQuery('#fa5 ul li.active').length){
                jQuery('#fa5-container').scrollTo(jQuery('#fa5 ul li.active').position().top - jQuery('#fa5 ul li.active').height() - 30);
            }
        });
    },
    add: function(){
        arCU.resetForm();
        jQuery('#arcontactus-modal-title').html(arCU.addTitle);
        jQuery('#arcontactus-modal').uiModal({
            closable: false
        }).uiModal('show');
    },
    populateForm: function(data){
        jQuery.each(data, function(i){
            var fieldId = '#arcontactus_' + i;
            if (i == 'icon'){
                jQuery('#arcontactus-icon-dropdown').dropdown('set selected', data[i]);
            }else if(i == 'color'){
                document.getElementById('arcontactus_color').jscolor.fromString(data[i]);
            }else{
                if (typeof data[i] == 'object') {
                    jQuery.each(data[i], function(ii){
                        var fieldId = '#arcontactus_' + i + '_' + ii;
                        jQuery(fieldId).val(data[i][ii]);
                    });
                } else {
                    jQuery(fieldId).val(data[i]);
                }
            }
        });
        
        jQuery('#arcontactus_color').trigger('keyup');
        arcontactusChangeType();
    },
    edit: function(id){
        arCU.resetForm();
        jQuery('#arcontactus-modal-title').html(arCU.editTitle);
        arCU.blockUI('#arcontactus-menu-items');
        jQuery.ajax({
            type: 'GET',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: {
                action : 'arcontactus_edit_menu_item',
                _wpnonce: arCU.nonce,
                ajax : true,
                id: id
            },
            success: function(data){
                jQuery('#arcontactus-modal').uiModal({
                    closable: false
                }).uiModal('show');
                arCU.populateForm(data);
                arCU.unblockUI('#arcontactus-menu-items');
                jQuery('#arcu-shortcode-group').removeClass('hidden');
            }
        }).fail(function(){
            arCU.unblockUI('#arcontactus-modal');
            arCU.showErrorMessage(arCU.errorMessage);
        });
    },
    toggle: function(id){
        jQuery.ajax({
            type: 'POST',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: {
                action : 'arcontactus_switch_menu_item',
                _wpnonce: arCU.nonce,
                id: id,
                ajax : true
            },
            success: function(data){
                arCU.reload();
            }
        }).fail(function(){
            arCU.showErrorMessage(arCU.errorMessage);
        });
    },
    _getFormData: function(){
        var params = {};
        jQuery('#arcontactus-form [data-serializable="true"]').each(function(){
            if (jQuery(this).data('lang-field')) {
                if (typeof params[jQuery(this).attr('name')] == 'undefined'){
                    params[jQuery(this).attr('name')] = {};
                }
                var lang = jQuery(this).data('lang-code');
                params[jQuery(this).attr('name')][lang] = jQuery(this).val();
            } else {
                params[jQuery(this).attr('name')] = jQuery(this).val();
            }
        });
        
        return params;
    },
    save: function(){
        var params = arCU._getFormData();
        arCU.blockUI('#arcontactus-modal');
        jQuery.ajax({
            type: 'POST',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: {
                action : 'arcontactus_save_menu_item',
                _wpnonce: arCU.nonce,
                ajax : true,
                data: params,
                lang: jQuery('#arcontactus_id_lang').val(),
                id: jQuery('#arcontactus_id').val()
            },
            success: function(data){
                arCU.unblockUI('#arcontactus-modal');
                if (!arCU.processErrors(data)){
                    arCU.showSuccessMessage(arCU.successSaveMessage);
                    jQuery('#arcontactus-modal').uiModal('hide');
                    arCU.reload();
                }
            }
        }).fail(function(){
            arCU.unblockUI('#arcontactus-modal');
            arCU.showErrorMessage(arCU.errorMessage);
        });
    },
    clearErrors: function(data){
        jQuery('#arcontactus-form .field.has-error').removeClass('has-error');
    },
    processErrors: function(data){
        arCU.clearErrors();
        if (data.success == 0){
            jQuery.each(data.errors, function(index){
                jQuery('#arcontactus_'+index).parents('.field').addClass('has-error');
                jQuery('#arcontactus_'+index).parents('.field').find('.errors').html(data.errors[index].join('<br/>'));
            });
            arCU.showErrorMessage(arCU.errorMessage);
            return true;
        }
        return false;
    },
    remove: function(id){
        if (!confirm('Delete this item?')){
            return false;
        }
        jQuery.ajax({
            type: 'POST',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: {
                action : 'arcontactus_delete_menu_item',
                _wpnonce: arCU.nonce,
                ajax : true,
                id: id
            },
            success: function(data){
                arCU.showSuccessMessage(arCU.successDeleteMessage);
                arCU.reload(true);
            }
        }).fail(function(){
            arCU.showErrorMessage(arCU.errorMessage);
        });
    },
    updateOrder: function(table, silent){
        var positions = [];
        jQuery(table).find('tbody tr').each(function(index){
            var order = index + 1;
            var id = jQuery(this).data('id');
            positions.push(id + '_' + order);
        });
        arCU.blockUI(table);
        if (arCU.prevOrder != positions){
            jQuery.ajax({
                type: 'POST',
                url: arCU.ajaxUrl,
                dataType: 'json',
                data: {
                    action : 'arcontactus_reorder_menu_items',
                    _wpnonce: arCU.nonce,
                    ajax : true,
                    data: positions
                },
                success: function(data){
                    arCU.unblockUI(table);
                    arCU.prevOrder = positions;
                    if (!silent){
                        //arCU.showSuccessMessage(arCU.successOrderMessage);
                    }
                    jQuery(table).find('tbody tr').each(function(index){
                        var order = index + 1;
                        jQuery(this).find('.position').text(order);
                    });
                }
            }).fail(function(){
                arCU.unblockUI(table);
                arCU.showErrorMessage(arCU.errorMessage);
            });
        }
    },
    reload: function(reorder){
        arCU.blockUI('#arcontactus-menu-items');
        jQuery.ajax({
            type: 'POST',
            url: arCU.ajaxUrl,
            dataType: 'json',
            data: {
                _ajax_nonce: arCU.nonce,
                _wpnonce: arCU.nonce,
                action : 'arcontactus_reload_menu_items',
                ajax : true,
            },
            success: function(data){
                arCU.unblockUI('#arcontactus-menu-items');
                jQuery('#arcontactus-menu-items').replaceWith(data.content);
                arCU.init();
                if (reorder){
                    arCU.updateOrder('#arcontactus-menu-items', true);
                }
            }
        }).fail(function(){
            arCU.showErrorMessage(arCU.errorMessage);
        });
    },
    resetForm: function(){
        jQuery('#arcontactus-form [data-default]').each(function(){
            var attr = jQuery(this).attr('data-default');
            if (typeof attr !== typeof undefined && attr !== false) {
                jQuery(this).val(jQuery(this).data('default'));
            }
        });
        jQuery('#arcu-shortcode-group').addClass('hidden');
        jQuery('#arcontactus-icon-dropdown').dropdown('set selected', '');
        document.getElementById('arcontactus_color').jscolor.fromString('000000');
        arCU.clearErrors();
        arcontactusChangeType();
        
    },
    blockUI: function(selector){
        jQuery(selector).addClass('ar-blocked');
        jQuery(selector).find('.ar-loading').remove();
        jQuery(selector).append('<div class="ar-loading"><div class="ar-loading-inner">Loading...</div></div>');
    },
    unblockUI: function(selector){
        jQuery(selector).find('.ar-loading').remove();
        jQuery(selector).removeClass('ar-blocked');
    },
    showSuccessMessage: function(mesage){
        
    },
    showErrorMessage: function(message){
        
    }
};