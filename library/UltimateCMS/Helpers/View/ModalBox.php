<?php

class ModalBox extends Zend_View_Helper_Abstract
{
    /**
     * Modal method is helper for show-hide-delete actions.
     * In your action buttons you need to specify:
     * <!> data-id="status" | Unique identificator must be used just for this actions.
     * <!> data-action="show/hide/delete" | Must be values from example left.
     * <!> data-module-id="<?php echo $module['id'];?>" | ID of module you want to manipulate (show/hide/delete)
     * <EXAMPLE>
     * <a data-id="status" data-action="show" data-page-id="<?php echo $page['id'];?>"> </a>
     * @callHelper 'echo $this->getHelper('Dashboard')->modal('page', 'table');'
     * Argument num 2 (table) is id of table/div/section for siblings catching
     * @param string $moduleName
     * @return Javascript popup with show/hide/delete actions.
     */
    public function modalBox($moduleName = null)
    {
        $this->view = new Zend_View();
        $this->view->headLink()->appendStylesheet($this->view->baseUrl('/skins/backend/global/plugins/bootstrap-sweetalert/sweetalert.css'));
        $this->view->inlineScript()->appendFile($this->view->baseUrl('/skins/backend/pages/scripts/ui-sweetalert.min.js'));
        $this->view->inlineScript()->appendFile($this->view->baseUrl('/skins/backend/global/plugins/bootstrap-sweetalert/sweetalert.min.js'));
        $front = Zend_Controller_Front::getInstance();
        $ctrl = $front->getRequest()->getControllerName();
        ?>

        <form method="post" id="modal" class="modal fade" role="dialog">
            <input type="hidden" name="task" value="">
            <input type="hidden" name="id" value="">
        </form>

        <?php $this->view->headStyle()->captureStart(); ?>
        .btn-primary {
            background-color: #8775a7 !important;
            border-color: #8775a7 !important;;
        }
        .btn-primary.active, .btn-primary:active, .btn-primary:hover, .open>.btn-primary.dropdown-toggle {
            color: #fff;
            background-color: #736292;
            border-color: #736292;
        }
        <?php $this->view->headStyle()->captureEnd(); ?>

            <?php $this->view->inlineScript()->captureStart();?>

            String.prototype.capitalizeFirstLetter = function() {
                return this.charAt(0).toUpperCase() + this.slice(1);
            }

            var moduleName = '<?php echo $moduleName; ?>';

            $('[data-id="status"]').on('click', function() {
                var parent = $(this).is('[data-id="status"]') ? $(this) : $(this).closest('[data-id="status"]');
                var dataAction = parent.attr('data-action');
                var statuses = {
                    'show':   {  'color': '#8775a7', 'message': 'You want to show',                'title': 'visible!'},
                    'hide':   {  'color': '#8775a7', 'message': 'You want to hide',                'title': 'hidden!' },
                    'delete': {  'color': '#EF5350', 'message': 'You will not be able to recover', 'title': 'deleted!'},
                };
                displayModal(dataAction, parent);

                function displayModal(dataAction, parent) {
                    var identificator = parent.attr('data-'+moduleName+'-id');
                    $("#modal").attr("action", "<?php echo $this->view->url(array(
                        'controller' => $ctrl, 'action' => 'status'
                    ), 'default', true) ;?>");
                    $('#modal').find('[name="id"]').val(identificator);
                    $('#modal').find('[name="task"]').val(dataAction);
                    swal({
                            title: "Are you sure?",
                            text: statuses[dataAction].message + ' ' +moduleName,
                            confirmButtonColor: statuses[dataAction].color,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'Yes, ' +dataAction+ ' it!',
                            cancelButtonText: "No, cancel!",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        },
                        function(isConfirm){
                            if (isConfirm) {
                                swal({
                                    title: statuses[dataAction].title.capitalizeFirstLetter(),
                                    text: moduleName.capitalizeFirstLetter() + ' is ' +statuses[dataAction].title,
                                    confirmButtonColor: dataAction.color,
                                    type: "success"
                                });
                                setTimeout(function(){ $('#modal').submit(); }, 1000);
                            }
                            else {
                                dataAction = dataAction.slice(-1) == 'e' ? dataAction.substring(-1, dataAction.length-1) : dataAction;
                                swal({
                                    title: "Cancelled",
                                    text: moduleName.capitalizeFirstLetter() + ' ' +dataAction +'ing is cancelled',
                                    confirmButtonColor: "#2196F3",
                                    type: "error"
                                });
                            }
                        });
                };
            });
            <?php $this->view->inlineScript()->captureEnd(); ?>
        </script>
        <?php
    }
}