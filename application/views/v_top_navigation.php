<?php
$set_search_keywords = '';
$get_search_keywords = $this->input->get('k', true);
if($get_search_keywords){
    $set_search_keywords = $get_search_keywords;
}
?>
<section id="header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <nav class="navbar navbar-expand-lg row">
                    <div class="col-lg-0 col-md-2 col-sm-2 col-xs-2 col-3 no-padding-l mobile-view">
                        <button type="button" class="drawer-toggle drawer-hamburger">
                            <span class="sr-only">toggle navigation</span>
                            <span class="drawer-hamburger-icon"></span>
                        </button>
                    </div>
                    <div class="col-lg-3 col-md-8 col-sm-8 col-xs-8 col-6 no-padding-l
					no-padding-l-tablet">
                        <a class="navbar-brand" href="<?php echo base_url();?>"><img class="img-fluid" src="<?php echo base_url('assets/images/logo-header.png').'?v1';?>"></a>
                    </div>
                    <div class="col-lg-0 col-md-2 col-sm-2 col-xs-2 col-3 mobile-view whatsapp">
                        <?php 
						if(isset($header_whatsapp) && !empty($header_whatsapp)){
							echo '<a target="_blank" href="https://api.whatsapp.com/send?phone=62'.$header_whatsapp[0]['setting_value'].'"><img class="img-fluid" src="'.base_url('assets/images/logo-wa.png').'"></a>';
						}
						?>
                    </div>
                    <div class="offset-lg-2 col-lg-7 col-md-12 col-sm-12 col-xs-12 no-padding-r">
                        <?php
                            $search = 'search';
                            if($this->uri->segment(1) == 'demo_search'){
                                $search = 'demo_search';
                            }
                        ?>
                        <form class="mx-lg-2 my-lg-auto d-inline w-100" action="<?php echo base_url($search);?>" method="get">
                            <div class="input-group my-md-3 my-sm-3 my-3">
                                <input id="search-header" type="search" name="k" class="search-header form-control" placeholder="Search popular characters, themes..." value="<?php echo $set_search_keywords; ?>">
                                <span class="input-group-append mr-lg-4">
                                    <button class="btn btn-teal" type="submit"><i class="fa fa-search fa-lg"></i></button>
                                </span>
                                <?php 
								if(isset($header_whatsapp) && !empty($header_whatsapp)){
									echo '<span class="input-group-append desktop-view">';
									echo '<a target="_blank" href="https://api.whatsapp.com/send?phone=62'.$header_whatsapp[0]['setting_value'].'"><img class="img-fluid" src="'.base_url('assets/images/logo-wa.png').'"></a>';
									echo '</span>';
								}
								?>
                            </div>
                        </form>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>