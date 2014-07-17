<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $page_title . " &middot; " . SITE_NAME; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo base_url(); ?>assets/css/<?php echo THEME; ?>.css" rel="stylesheet">
        <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
        <script> var JS_DATE = '<?php echo JS_DATE; ?>';
        $(function(){$('input.tip, select.tip').tooltip({placement:"right",trigger:"focus"});$('.tip').tooltip();$(".chzn-select").on("liszt:showing_dropdown",function(){$(this).parents("div").css("overflow","visible")});$(".chzn-select").on("liszt:hiding_dropdown",function(){$(this).parents("div").css("overflow","")});<?php if(THEME=='rtl'){ ?>$(".chzn-container, form select").addClass('chzn-rtl');<?php } ?>$("form select").chosen({no_results_text:"<?php echo $this->lang->line('no_results_matched'); ?>",disable_search_threshold:5,allow_single_deselect:true});$('#note').redactor({buttons:['formatting','|','alignleft','aligncenter','alignright','justify','|','bold','italic','underline','|','unorderedlist','orderedlist','|','image','video','link','|','html'],formattingTags:['p','pre','h3','h4'],imageUpload:'<?php echo site_url('module=home&view=image_upload&prefix=' . PREFIX); ?>',imageUploadErrorCallback:function(json){bootbox.alert(json.error)},minHeight:100});$('#internal_note').redactor({buttons:['formatting','|','alignleft','aligncenter','alignright','justify','|','bold','italic','underline','|','unorderedlist','orderedlist','|','image','video','link','|','html'],formattingTags:['p','pre','h3','h4'],imageUpload:'<?php echo site_url('module=home&view=image_upload'); ?>',imageUploadErrorCallback:function(json){bootbox.alert(json.error)},minHeight:100,placeholder:'<?php echo $this->lang->line('internal_note'); ?>'});$('.redactor_toolbar a').tooltip({container:'body'});$('.showSubMenus').click(function(){var sub_menu=$(this).attr('href');$('.sub-menu').slideUp('fast');$('.menu').find("b").removeClass('caret-up').addClass('caret');if($(sub_menu).is(":hidden")){$(sub_menu).slideDown("slow");$(this).find("b").removeClass('caret').addClass('caret-up')}else{$(sub_menu).slideUp();$(this).find("b").removeClass('caret-up').addClass('caret')}return false});$('.menu-collapse').click(function(){$('#col_1').slideToggle()})});$(window).resize(function(){if($(document).width()>980){$('#col_1').show()}});
        </script>
        <!--[if lt IE 9]>
              <script src="<?php echo base_url(); ?>assets/js/html5shiv.js"></script>
        <![endif]-->
        <?php
        if (!$this->ion_auth->in_group(array('owner', 'admin'))) {
            echo '<style>table tfoot { display: none !important; }</style>';
        }
        ?>
    </head>

    <body>
        <div id="wrap">
            <div class="navbar navbar-fixed-top">
                <div class="navbar-inner">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                    <button type="button" class="btn btn-navbar menu-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                    <div class="brand"><img src="<?php echo base_url(); ?>assets/img/<?php echo LOGO; ?>" alt="<?php echo SITE_NAME; ?>" style="height:30px;"/></div>
                    <?php if( FIRST_NAME != "FIRST_NAME"){?> 
                    <ul class="hidden-desktop nav pull-right"><li><a class="hdate"> <?php echo date('l, F d, Y'); ?> </a></li></ul>
                    
                    <div class="nav-collapse collapse">
                        <ul class="nav pull-right">
                            <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hi, <?php echo FIRST_NAME; ?>! <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo base_url(); ?>index.php?module=auth&amp;view=change_password&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('change_password'); ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=auth&amp;view=logout&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('logout'); ?></a></li>
                                </ul>
                            </li>
                            <li class="divider-vertical"></li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="<?php echo base_url(); ?>assets/img/<?php echo LANGUAGE; ?>.png" style="margin-top:-1px" align="middle"></a>
                                <ul class="dropdown-menu pull-right" style="min-width: 60px;" role="menu" aria-labelledby="dLabel">
                                <?php $scanned_lang_dir = array_diff(scandir('sma/language/'), array('..', '.', 'index.html')); foreach ($scanned_lang_dir as $entry) { ?>
                                    <li><a href="<?php echo site_url('module=home&view=language&lang=' . $entry . '&prefix=' . PREFIX); ?>"><img src="<?php echo base_url(); ?>assets/img/<?php echo $entry; ?>.png" class="language-img"> &nbsp;&nbsp;<?php if ($entry == 'bportuguese') { echo "Brazilian Portuguese"; } elseif ($entry == 'eportuguese') { echo "European Portuguese"; } else { echo ucwords($entry); } ?></a></li>
                                <?php } ?></ul>
                            </li>
                        </ul>
                        <ul class="nav pull-right">
                            <li class="visible-desktop"><a class="hdate"><?php echo date('l, j F Y'); ?></a></li>
                            <li><a href="index.php?module=home&amp;prefix=<?php echo PREFIX; ?>"><i class="icon-home icon-white"></i> <?php echo $this->lang->line('home'); ?></a></li>
                            <li><a href="index.php?module=calendar&amp;prefix=<?php echo PREFIX; ?>"><i class="icon-calendar icon-white"></i>  <?php echo $this->lang->line('calendar'); ?></a></li>
                            <?php if (UP_EVENTS) { ?>
                                <li class="dropdown dropdown-big"> <a class="dropdown-toggle" href="#" data-toggle="dropdown"> <i class="icon-list icon-white"></i> <?php echo $this->lang->line('upcoming_events'); ?> </a>
                                    <ul class="dropdown-menu">
                                <?php echo UP_EVENTS; ?>
                                    </ul>
                                </li>
                            <?php } ?>
                                
                            <?php if ($this->ion_auth->in_group(array('owner', 'admin'))) { ?>
                                                            <li id="eann"><a href="#myModal" role="button" data-toggle="modal"><i class="icon-edit icon-white"></i>  <?php echo $this->lang->line('edit_ann'); ?></a></li>
                            <?php } ?>
                            <?php
                            if (file_exists('sma/modules/pos/controllers/pos.php') && is_dir('sma/modules/pos')) {
                                echo '<li><a href="index.php?module=pos&prefix=' . PREFIX . '" class="btn btn-success hbtn">'.$this->lang->line("point_of_sale").'</a></li>';
                            }
                            if (ALERT_NO > 0) {
                                echo "<li><a class=\"btn btn-warning hbtn\" href=\"index.php?module=reports&view=products&prefix=" . PREFIX . "\">" . ALERT_NO . " " . $this->lang->line('product_alerts') . "</a></li>";
                            }
                            if (DEMO) {
                                echo '<li><a class="btn btn-success hbtn" href="http://codecanyon.net/item/stock-manager-advance-invoice-inventory-system/3647040?ref=tecdiary" target="_blank">Buy Now</a></li>';
                            }
                            ?>	
                            <li class="divider-vertical"></li>
                        </ul>
                    </div>
                    <?php }else{ ?>
                    <a href="<?php echo base_url(); ?>index.php?module=auth&amp;view=login&amp;prefix=<?php echo PREFIX; ?>" class="btn btn-success" style="float:right;margin-right: 20px;"><?php echo $this->lang->line("login_page"); ?></a>
                    <div style="clear:both;"></div>
                    <?php }?>
                </div>
            </div>
            <div id="col_1">
                <div id="mainmanu">
<?php if ($this->ion_auth->in_group(array('owner', 'admin'))) { ?>
                        <ul class="menu nav nav-tabs nav-stacked">
                            <li class="dropdown"><a class="showSubMenus" href="#productsMenu"><i class="icon-barcode icon-white"></i> <?php echo $this->lang->line('products'); ?> <b class="caret"></b></a>
                                <ul class="nav nav-tabs nav-stacked sub-menu" id="productsMenu">
                                    <li><a href="<?php echo base_url(); ?>index.php?module=products&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_products'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=products&amp;view=add&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('new_product'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=products&amp;view=sheet&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('print_barcodes'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=products&amp;view=labels&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('print_labels'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=products&amp;view=upload_csv&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('add_by_csv'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=products&amp;view=update_price&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('update_price'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=products&amp;view=damage_products&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('damage_products'); ?></a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a class="showSubMenus" href="#transfersMenu"><i class="icon-random icon-white"></i> <?php echo $this->lang->line('transfers'); ?> <b class="caret"></b></a>
                                <ul class="nav nav-tabs nav-stacked sub-menu" id="transfersMenu">
                                    <li><a href="<?php echo base_url(); ?>index.php?module=transfers&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_transfers'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=transfers&amp;view=add&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('transfer_products'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=transfers&amp;view=transfer_csv&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('transfer_products_by_csv'); ?></a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a class="showSubMenus" href="#purchasesMenu"><i class="icon-star icon-white"></i> <?php echo $this->lang->line('purchases'); ?> <b class="caret"></b></a>
                                <ul class="nav nav-tabs nav-stacked sub-menu" id="purchasesMenu">
                                    <li><a href="<?php echo base_url(); ?>index.php?module=inventories&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_purchases'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=inventories&amp;view=add&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('add_purchase'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=inventories&amp;view=csv_inventory&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('csv_inventory'); ?></a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a class="showSubMenus" href="#salesMenu"><i class="icon-heart  icon-white"></i> <?php echo $this->lang->line('sales'); ?> <b class="caret"></b></a>
                                <ul class="nav nav-tabs nav-stacked sub-menu" id="salesMenu">
                                    <li><a href="<?php echo base_url(); ?>index.php?module=sales&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_invoices'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=sales&amp;view=add&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('add_invoice'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=sales&amp;view=deliveries&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_deliveries'); ?></a></li>
                                </ul>
                            </li>
                            <li class="dropdown" style="display:none;"><a class="showSubMenus" href="#quotesMenu"><i class="icon-exclamation-sign icon-white"></i> <?php echo $this->lang->line('quotes'); ?> <b class="caret"></b></a>
                                <ul class="nav nav-tabs nav-stacked sub-menu" id="quotesMenu">
                                    <li><a href="<?php echo base_url(); ?>index.php?module=quotes&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_quotes'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=quotes&amp;view=add&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('add_quote'); ?></a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a class="showSubMenus" href="#peopleMenu"><i class="icon-user  icon-white"></i> <?php echo $this->lang->line('people'); ?> <b class="caret"></b></a>
                                <ul class="nav nav-tabs nav-stacked sub-menu" id="peopleMenu">
    <?php if ($this->ion_auth->in_group('owner')) { ?>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=auth&amp;view=users&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_users'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=auth&amp;view=create_user&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('new_user'); ?></a></li>
                                        <li class="divider"></li>
    <?php } ?>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=billers&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_billers'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=billers&amp;view=add&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('new_biller'); ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=suppliers&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_suppliers'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=suppliers&amp;view=add&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('new_supplier'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=suppliers&amp;view=add_by_csv&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('add_suppliers_by_csv'); ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=customers&amp;view=giftcard&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_giftcard'); ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=customers&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_customers'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=customers&amp;view=add&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('new_customer'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=customers&amp;view=add_by_csv&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('add_customers_by_csv'); ?></a></li>
                                	
                                </ul>
                            </li>
    <?php if ($this->ion_auth->in_group('owner')) { ?>
                                <li class="dropdown"><a class="showSubMenus" href="#settingsMenu"><i class="icon-cog  icon-white"></i> <?php echo $this->lang->line('settings'); ?> <b class="caret"></b></a>
                                    <ul class="nav nav-tabs nav-stacked sub-menu" id="settingsMenu">
                                        <li><a href="<?php echo base_url(); ?>index.php?module=settings&amp;view=system_setting&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('system_setting'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=settings&amp;view=change_logo&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('chnage_logo'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=settings&amp;view=upload_biller_logo&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('upload_biller_logo'); ?></a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=categories&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_categories'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=categories&amp;view=add&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('new_category'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=categories&view=subcategories&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_subcategories'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=categories&amp;view=add_subcategory&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('new_subcategory'); ?></a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=settings&amp;view=warehouses&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('warehouses'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=settings&amp;view=add_warehouse&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('add_warehouse'); ?></a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=settings&amp;view=tax_rates&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('tax_rates'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=settings&amp;view=add_tax_rate&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('add_tax_rate'); ?></a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=settings&amp;view=discounts&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('discounts'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=settings&amp;view=add_discount&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('add_discount'); ?></a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=settings&amp;view=backup_database&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('backup_database'); ?></a></li>
                                    </ul>
                                </li>
                        <?php } ?>
                            <li class="dropdown"><a class="showSubMenus" href="#reportsMenu"><i class="icon-tasks  icon-white"></i> <?php echo $this->lang->line('reports'); ?> <b class="caret"></b></a>
                                <ul class="nav nav-tabs nav-stacked sub-menu" id="reportsMenu">
                                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&view=overview&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('overview_chart'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&view=warehouse_stock&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('warehouse_stock_value'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&view=products&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('product_alerts'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&view=custom_products&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('products_report'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&view=purchases&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('purchase_report'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&view=daily_sales&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('daily_sales'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&view=monthly_sales&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('monthly_sales'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&view=sales&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('sales_report'); ?></a></li>
                                </ul>
                            </li>
                        </ul>
                                <?php } ?>
                                <?php if ($this->ion_auth->in_group(array('salesman', 'purchaser'))) { ?>
                        <ul class="menu nav nav-tabs nav-stacked">
                            <li class="dropdown"><a class="showSubMenus" href="#userMenu"><i class="icon-tasks  icon-white"></i> <?php echo $this->lang->line('menus'); ?> <b class="caret"></b></a>
                                <ul class="nav nav-tabs nav-stacked sub-menu" id="userMenu" style="display:block;">
                                    <li><a href="<?php echo base_url(); ?>index.php?module=products&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_products'); ?></a></li>
                                    <?php if ($this->ion_auth->in_group('purchaser')) { ?>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=inventories&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_purchases'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=inventories&amp;view=add&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('add_purchase'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=inventories&amp;view=csv_inventory&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('csv_inventory'); ?></a></li>
                                    <?php } if ($this->ion_auth->in_group('salesman')) { ?>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=sales&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_invoices'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=sales&amp;view=add&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('add_invoice'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=quotes&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_quotes'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=quotes&amp;view=add&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('add_quote'); ?></a></li>
    <?php } ?>
                        <?php if ($this->ion_auth->in_group('purchaser')) { ?>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=suppliers&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_suppliers'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=suppliers&amp;view=add&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('new_supplier'); ?></a></li>
    <?php } if ($this->ion_auth->in_group('salesman')) { ?>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=customers&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_customers'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=customers&amp;view=add&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('new_customer'); ?></a></li>
    <?php } ?>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&view=products&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('product_alerts'); ?></a></li>
    <?php if ($this->ion_auth->in_group('purchaser')) { ?>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=reports&view=purchases&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('purchase_report'); ?></a></li>
    <?php } if ($this->ion_auth->in_group('salesman')) { ?>
                                        <li><a href="<?php echo base_url(); ?>index.php?module=reports&view=sales&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('sales_report'); ?></a></li>
    <?php } ?>
                                </ul>
                            </li>
                        </ul>
<?php } elseif ($this->ion_auth->in_group('viewer')) { ?>
                        <ul class="menu nav nav-tabs nav-stacked">
                            <li class="dropdown"><a class="showSubMenus" href="#userMenu"><i class="icon-tasks  icon-white"></i> <?php echo $this->lang->line('menus'); ?> <b class="caret"></b></a>
                                <ul class="nav nav-tabs nav-stacked sub-menu" id="userMenu" style="display:block;">
                                    <li><a href="<?php echo base_url(); ?>index.php?module=products&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_products'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=inventories&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_purchases'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=sales&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('list_invoices'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=auth&view=change_password&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('change_password'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php?module=auth&view=logout&amp;prefix=<?php echo PREFIX; ?>"><?php echo $this->lang->line('logout'); ?></a></li>

                                </ul>
                            </li>
                        </ul>
<?php } ?>
                </div>
            </div>
            <div id="contenitore_col_2">
                <div id="col_2">
                    <div class="main-content">
                        <div class="row-fluid">
