<?php

class Zend_View_Helper_Dashboard extends Zend_View_Helper_Abstract
{
    /**
     * Self point method so we can access all other
     * functions outside the helper. We can achieve modularity in application
     * <b>
     * @uses $this->getHelper('HelperName')->methodName() 
     * This is how to use the same just call method getHelper outside the class 
     * And continue in chain with methods.
     * </b>
     * @return $this
     */
    protected function dashboard() {
        return $this;
    }
    
    public function sidebar() {
        $modelSidebar = new Model_Admin_Sidebar_Sidebar();
        $sidebarResources = $modelSidebar->getSidebarResources();
        $front = Zend_Controller_Front::getInstance();
        $ctrl = $front->getRequest()->getControllerName();
        $action = $front->getRequest()->getActionName();
    ?>
        <div class="page-sidebar-wrapper" style="margin-top: -20px;">
            <div class="page-sidebar navbar-collapse collapse">
                <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                    <li class="sidebar-toggler-wrapper hide">
                        <div class="sidebar-toggler">
                            <span></span>
                        </div>
                    </li>
                    <li class="nav-item start active open">
                        <a href="/admin_dashboard" class="nav-link nav-toggle">
                            <i class="icon-speedometer"></i>
                            <span class="title">CMS HOME</span>
                            <span class="selected"></span>
                            <span class="arrow open"></span>
                        </a>
                    </li>
                    <li class="heading">
                        <h3 class="uppercase">MENADŽMENT SAJTA</h3>
                    </li>
                    <?php $i = 0; foreach ($sidebarResources as $key => $resource): ?>
                        <?php
                            $currentController = $sidebarResources[$key]['label']['data'][0]['controller'];
                            $open = ($currentController == $ctrl ? 'open' : '');
                            $block = ($currentController == $ctrl ? 'style="display:block"' : '');
                        ?>
                        <li class="nav-item <?php echo $open;?>">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="<?php echo $resource['label']['icon']; ?>"></i>
                                <span class="title"><?php echo $resource['label']['title']; ?></span>
                                <span class="arrow <?php echo $open;?>"></span>
                            </a>
                            <?php if (count($sidebarResources[$key]['label']['data']) > 0): ?>
                                <ul class="sub-menu" <?php echo $block ;?>>
                                    <?php foreach ($sidebarResources[$key]['label']['data'] as $data):?>
                                        <?php
                                            $child = ($data['action'] == $action) &&
                                            ($data['controller'] == $ctrl) ? 'active open' : '';
                                        ?>
                                        <li class="nav-item <?php echo $child; ?>">
                                            <a href="<?php echo $this->view->url(array(
                                                    'controller' => $data['controller'],
                                                    'action' => $data['action']
                                                  ),'default', true);?>" class="nav-link ">
                                                <span class="title"><?php echo $data['title'];?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                        <?php if ($i == 4):?>
                            <li class="heading">
                                <h3 class="uppercase">DIZAJN SAJTA</h3>
                            </li>
                        <?php elseif ($i == 5):?>
                            <li class="heading">
                                <h3 class="uppercase">OPŠTE</h3>
                            </li>
                        <?php endif; ?>
                    <?php $i++; endforeach; ?>

                    <li class="nav-item ">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="icon-user"></i>
                            <span class="title">Korisnici</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item  ">
                                <a href="" class="nav-link ">
                                    <span class="title">Administracija</span>
                                </a>
                            </li>
                            <li class="nav-item  ">
                                <a href="" class="nav-link ">
                                    <span class="title">Novi korisnik</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item  ">
                        <a href="" class="nav-link ">
                            <i class="icon-folder-alt"></i>
                            <span class="title">Menadžer fajlova</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="" class="nav-link ">
                            <i class="icon-wallet"></i>
                            <span class="title">Marketing kampanje</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title">Podešavanja</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item  ">
                                <a href="" class="nav-link ">
                                    <span class="title">Opšte</span>
                                </a>
                            </li>
                            <li class="nav-item  ">
                                <a href="" class="nav-link ">
                                    <span class="title">Linkovi (Friendly URL)</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="heading">
                        <h3 class="uppercase">PROGRAMER</h3>
                    </li>

                    <li class="nav-item  ">
                        <a href="" class="nav-link ">
                            <i class="icon-list"></i>
                            <span class="title">CMS Sidebar</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="" class="nav-link ">
                            <i class="icon-key"></i>
                            <span class="title">Privilegije</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="" class="nav-link ">
                            <i class="icon-cloud-download"></i>
                            <span class="title">Backup</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    <?php }

    public function breadcrumbs() { ?>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="">CMS-G | Dashboard</a>
                </li>
            </ul>
        </div>
    <?php }

    public function language() { ?>
        <li class="dropdown dropdown-language">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
                <img alt="" src="http://keenthemes.com/preview/metronic/theme/assets/global/img/flags/us.png">
                <span class="langname"> US </span>
                <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-default">
                <li>
                    <a href="javascript:;">
                        <img alt="" src="http://keenthemes.com/preview/metronic/theme/assets/global/img/flags/es.png"> Spanish </a>
                </li>
                <li>
                    <a href="javascript:;">
                        <img alt="" src="http://keenthemes.com/preview/metronic/theme/assets/global/img/flags/de.png"> German </a>
                </li>
                <li>
                    <a href="javascript:;">
                        <img alt="" src="http://keenthemes.com/preview/metronic/theme/assets/global/img/flags/ru.png"> Russian </a>
                </li>
                <li>
                    <a href="javascript:;">
                        <img alt="" src="http://keenthemes.com/preview/metronic/theme/assets/global/img/flags/fr.png"> French </a>
                </li>
            </ul>
        </li>
    <?php }

    public function systemMessagesHtml($messages)
    {
        $this->view->inlineScript()->appendFile($this->view->baseUrl('/skins/backend/js/plugins/notifications/pnotify.min.js'));
        $this->view->inlineScript()->appendFile($this->view->baseUrl('/skins/backend/js/pages/components_notifications_pnotify.js'));

        $this->view->placeholder('systemMessagesHtml')->exchangeArray(array());
        if (!empty($messages['success'])) {

            foreach ($messages['success'] as $message) {
                $this->view->placeholder('systemMessagesHtml')->captureStart();
                ?>
                <script>
                    <?php $this->view->inlineScript()->captureStart();?>
                    $(document).ready(function() {
                        $(function(){ new PNotify({
                            title: 'Success!',
                            text: '<?= $this->view->escape($message);?>',
                            icon: 'icon-checkmark3',
                            type: 'success',
                            addclass: 'bg-success'
                        });
                        });
                    });
                    <?php $this->view->inlineScript()->captureEnd();?>
                </script>
                <?php
                $this->view->placeholder('systemMessagesHtml')->captureEnd();
            }
        }

        if (!empty($messages['errors'])) {

            foreach ($messages['errors'] as $message) {
                $this->view->placeholder('systemMessagesHtml')->captureStart();
                ?>
                <script>
                    <?php $this->view->inlineScript()->captureStart();?>
                    $(document).ready(function() {
                        $(function(){ new PNotify({
                            title: 'Right icon',
                            text: '<?= $this->view->escape($message);?>',
                            addclass: 'alert alert-warning alert-styled-right',
                            type: 'error',
                            addclass: 'bg-danger'
                        });
                        });
                    });
                    <?php $this->view->inlineScript()->captureEnd();?>
                </script>
                <?php
                $this->view->placeholder('systemMessagesHtml')->captureEnd();
            }
        }
        return $this->view->placeholder('systemMessagesHtml')->toString();
        ?>
        <?php
    }
}