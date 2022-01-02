<?php 
use Adminz\Admin\Adminz as Adminz;
add_action('ux_builder_setup', 'adminz_map');
add_shortcode('adminz_map', 'adminz_map_function');
function adminz_map(){
    add_ux_builder_shortcode('adminz_map', array(
        'type' => 'container',
        'allow' => array( 'adminz_map-item' ),
        'name'      => __('Open Street Map'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'map' . '.svg',
        'options' => array(
            'map_config'=> [
                'type' => 'group',
                'heading'=> 'Map config',
                'options'=> [
                    'layout' => array(
                        'type' => 'select',
                        'heading' => __( 'Layout' ),                        
                        'options'=> [
                            '0'=> "Default",
                            '1'=> "Right list",
                            '2'=> "Absolute list",
                        ],
                        'default'=>'1'
                    ),
                    'mapzoom' => array(
                        'type' => 'slider',
                        'heading' => __( 'Map zoom' ),
                        'min' => 1,
                        'max'=> 18,
                        'default' => 5,
                    ),
                    'mapheight' => array(
                        'type' => 'slider',
                        'unit' => 'px',
                        'heading' => __( 'Map height' ),                
                        'default' => 500,
                        'max' => 1000,
                        'min'=> 200,
                        'step' => 10
                    ),
                    'view_map'=> array(
                        'type' => 'checkbox',
                        'heading' => "View Location url",
                        'default' => 'true'
                    ),
                    'mapstyle' => array(
                        'type' => 'select',
                        'heading' => __( 'Map style' ),
                        'description'=> 'Leaflet Provider',
                        'default' => 'OpenStreetMap_Mapnik',
                        'options'=> [
                            'default'=>'Default', 'OpenStreetMap_Mapnik'=> 'OpenStreetMap_Mapnik', 'OpenStreetMap_DE'=> 'OpenStreetMap_DE', 'OpenStreetMap_CH'=> 'OpenStreetMap_CH', 'OpenStreetMap_France'=> 'OpenStreetMap_France', 'OpenStreetMap_HOT'=> 'OpenStreetMap_HOT', 'OpenStreetMap_BZH'=> 'OpenStreetMap_BZH', 'OpenTopoMap'=> 'OpenTopoMap', 'Stadia_AlidadeSmooth'=> 'Stadia_AlidadeSmooth', 'Stadia_AlidadeSmoothDark'=> 'Stadia_AlidadeSmoothDark', 'Stadia_OSMBright'=> 'Stadia_OSMBright', 'Stadia_Outdoors'=> 'Stadia_Outdoors', 'CyclOSM'=> 'CyclOSM', 'Hydda_Full'=> 'Hydda_Full', 'Hydda_Base'=> 'Hydda_Base', 'Stamen_Toner'=> 'Stamen_Toner', 'Stamen_TonerBackground'=> 'Stamen_TonerBackground', 'Stamen_TonerLite'=> 'Stamen_TonerLite', 'Stamen_Watercolor'=> 'Stamen_Watercolor', 'Stamen_Terrain'=> 'Stamen_Terrain', 'Stamen_TerrainBackground'=> 'Stamen_TerrainBackground', 'Stamen_TerrainLabels'=> 'Stamen_TerrainLabels', 'Stamen_TopOSMRelief'=> 'Stamen_TopOSMRelief', 'Thunderforest_OpenCycleMap'=> 'Thunderforest_OpenCycleMap', 'Thunderforest_Transport'=> 'Thunderforest_Transport', 'Thunderforest_TransportDark'=> 'Thunderforest_TransportDark', 'Thunderforest_SpinalMap'=> 'Thunderforest_SpinalMap', 'Thunderforest_Landscape'=> 'Thunderforest_Landscape', 'Thunderforest_Outdoors'=> 'Thunderforest_Outdoors', 'Thunderforest_Pioneer'=> 'Thunderforest_Pioneer', 'Thunderforest_MobileAtlas'=> 'Thunderforest_MobileAtlas', 'Thunderforest_Neighbourhood'=> 'Thunderforest_Neighbourhood', 'Jawg_Streets'=> 'Jawg_Streets', 'Jawg_Terrain'=> 'Jawg_Terrain', 'Jawg_Sunny'=> 'Jawg_Sunny', 'Jawg_Dark'=> 'Jawg_Dark', 'Jawg_Light'=> 'Jawg_Light', 'Jawg_Matrix'=> 'Jawg_Matrix', 'TomTom_Basic'=> 'TomTom_Basic', 'TomTom_Hybrid'=> 'TomTom_Hybrid', 'TomTom_Labels'=> 'TomTom_Labels', 'Esri_WorldStreetMap'=> 'Esri_WorldStreetMap', 'Esri_DeLorme'=> 'Esri_DeLorme', 'Esri_WorldTopoMap'=> 'Esri_WorldTopoMap', 'Esri_WorldImagery'=> 'Esri_WorldImagery', 'Esri_WorldTerrain'=> 'Esri_WorldTerrain', 'Esri_WorldShadedRelief'=> 'Esri_WorldShadedRelief', 'Esri_WorldPhysical'=> 'Esri_WorldPhysical', 'Esri_OceanBasemap'=> 'Esri_OceanBasemap', 'Esri_NatGeoWorldMap'=> 'Esri_NatGeoWorldMap', 'Esri_WorldGrayCanvas'=> 'Esri_WorldGrayCanvas', 'MtbMap'=> 'MtbMap', 'CartoDB_Positron'=> 'CartoDB_Positron', 'CartoDB_PositronNoLabels'=> 'CartoDB_PositronNoLabels', 'CartoDB_PositronOnlyLabels'=> 'CartoDB_PositronOnlyLabels', 'CartoDB_DarkMatter'=> 'CartoDB_DarkMatter', 'CartoDB_DarkMatterNoLabels'=> 'CartoDB_DarkMatterNoLabels', 'CartoDB_DarkMatterOnlyLabels'=> 'CartoDB_DarkMatterOnlyLabels', 'CartoDB_Voyager'=> 'CartoDB_Voyager', 'CartoDB_VoyagerNoLabels'=> 'CartoDB_VoyagerNoLabels', 'CartoDB_VoyagerOnlyLabels'=> 'CartoDB_VoyagerOnlyLabels', 'CartoDB_VoyagerLabelsUnder'=> 'CartoDB_VoyagerLabelsUnder', 'HikeBike_HikeBike'=> 'HikeBike_HikeBike', 'HikeBike_HillShading'=> 'HikeBike_HillShading', 'BasemapAT_basemap'=> 'BasemapAT_basemap', 'BasemapAT_grau'=> 'BasemapAT_grau', 'BasemapAT_overlay'=> 'BasemapAT_overlay', 'BasemapAT_terrain'=> 'BasemapAT_terrain', 'BasemapAT_surface'=> 'BasemapAT_surface', 'BasemapAT_highdpi'=> 'BasemapAT_highdpi', 'BasemapAT_orthofoto'=> 'BasemapAT_orthofoto', 'nlmaps_standaard'=> 'nlmaps_standaard', 'nlmaps_pastel'=> 'nlmaps_pastel', 'nlmaps_grijs'=> 'nlmaps_grijs', 'nlmaps_luchtfoto'=> 'nlmaps_luchtfoto', 'NASAGIBS_ModisTerraTrueColorCR'=> 'NASAGIBS_ModisTerraTrueColorCR', 'NASAGIBS_ModisTerraBands367CR'=> 'NASAGIBS_ModisTerraBands367CR', 'NASAGIBS_ViirsEarthAtNight2012'=> 'NASAGIBS_ViirsEarthAtNight2012', 'NLS'=> 'NLS', 'GeoportailFrance_plan'=> 'GeoportailFrance_plan', 'GeoportailFrance_parcels'=> 'GeoportailFrance_parcels', 'GeoportailFrance_orthos'=> 'GeoportailFrance_orthos', 'USGS_USTopo'=> 'USGS_USTopo', 'USGS_USImagery'=> 'USGS_USImagery', 'USGS_USImageryTopo'=> 'USGS_USImageryTopo',
                        ]
                    ),
                    'maptilelayer' => array(
                        'type' => 'select',
                        'heading' => __( 'Map tile layer' ),
                        'config'  => array(
                            'placeholder' => __( 'Select...', 'ux-builder' ),
                            'multiple'    => true,
                            'options'=> ['OpenSeaMap'=> 'OpenSeaMap', 'OpenPtMap'=> 'OpenPtMap', 'OpenRailwayMap'=> 'OpenRailwayMap', 'OpenFireMap'=> 'OpenFireMap', 'SafeCast'=> 'SafeCast', 'Hydda_RoadsAndLabels'=> 'Hydda_RoadsAndLabels', 'Stamen_TonerHybrid'=> 'Stamen_TonerHybrid', 'Stamen_TonerLines'=> 'Stamen_TonerLines', 'Stamen_TonerLabels'=> 'Stamen_TonerLabels', 'Stamen_TopOSMFeatures'=> 'Stamen_TopOSMFeatures', 'OpenWeatherMap_Clouds'=> 'OpenWeatherMap_Clouds', 'OpenWeatherMap_Pressure'=> 'OpenWeatherMap_Pressure', 'OpenWeatherMap_Wind'=> 'OpenWeatherMap_Wind', 'NASAGIBS_ModisTerraSnowCover'=> 'NASAGIBS_ModisTerraSnowCover', 'JusticeMap_income'=> 'JusticeMap_income', 'JusticeMap_americanIndian'=> 'JusticeMap_americanIndian', 'JusticeMap_asian'=> 'JusticeMap_asian', 'JusticeMap_black'=> 'JusticeMap_black', 'JusticeMap_hispanic'=> 'JusticeMap_hispanic', 'JusticeMap_multi'=> 'JusticeMap_multi', 'JusticeMap_nonWhite'=> 'JusticeMap_nonWhite', 'JusticeMap_white'=> 'JusticeMap_white', 'JusticeMap_plurality'=> 'JusticeMap_plurality', 'WaymarkedTrails_hiking'=> 'WaymarkedTrails_hiking', 'WaymarkedTrails_cycling'=> 'WaymarkedTrails_cycling', 'WaymarkedTrails_mtb'=> 'WaymarkedTrails_mtb', 'WaymarkedTrails_slopes'=> 'WaymarkedTrails_slopes', 'WaymarkedTrails_riding'=> 'WaymarkedTrails_riding', 'WaymarkedTrails_skating'=> 'WaymarkedTrails_skating', 'OpenAIP'=> 'OpenAIP',
                            ]
                        )                
                    ),
                    'apikey' => array(
                        'type' => 'textfield',
                        'heading' => __( 'API key' ),
                        'placeholder' => 'Your API key'
                    ),
                    'accesstoken' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Access token' ),
                        'placeholder' => 'Your Access token'
                    ),
                ]
            ],
            'search_config'=>[
                'type' => 'group',
                'heading'=> 'Form config',
                'options'=> [
                    'search_form' => array(
                        'type' => 'checkbox',
                        'heading' => __( 'Show search form' ),
                        'default'=> 'true'
                    ),
                    'list_items' => array(
                        'type' => 'checkbox',
                        'heading' => __( 'Show list items' ),
                        'default'=> 'true'
                    ),
                    'list_items_title' => array(
                        'type' => 'textfield',
                        'heading' => 'List Markers title',
                        'default' => '',                        
                    ),
                    'item_col' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Items collumns desktop' ),
                        'default' => '1'
                    ),
                    'item_col_mobile' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Items collumns mobile' ),
                        'default' => '1'
                    ),
                    'item_hover' => array(
                        'type' => 'checkbox',
                        'heading' => __( 'Items hover effect' ),
                        'default' => 'true'
                    ),
                    'field1' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Field 1 title' ),
                        'default' => 'Country'
                    ),
                    'field2' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Field 2 title' ),
                        'default' => 'City'
                    ),
                    'alltext' => array(
                        'type' => 'textfield',
                        'heading' => __( 'All place holder text' ),
                        'default' => 'All'
                    ),
                    'searchbutton' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Search button text' ),
                        'default' => 'Search'
                    ),
                ]
            ],                        
            
            'marker_config'=> array(
                'type' => 'group',
                'heading'=> 'Marker config',
                'options'=> array(
                    'markerzoom' => array(
                        'type' => 'slider',
                        'heading' => __( 'Marker zoom' ),                
                        'default' => 9,
                        'min'=>1,
                        'max'=> 18
                    ),
                    'markericon' => array(
                        'type' => 'image',
                        'heading' => __( 'Marker icon' ),     
                        'description'=> 'Default size: 25x41px'           
                    ),
                    'markericonshadow' => array(
                        'type' => 'image',
                        'heading' => __( 'Marker shadow' ),   
                        'description'=> 'Default size: 41x41px'             
                    ),
                )
            ),
            'other_config'=> array(
                'type'=> 'group',
                'heading'=> 'Other config',
                'options'=> array(
                    'address_text' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Address' ),
                        'default' => __( 'Address' ),
                    ),
                    'phone_number_text'=>array(
                        'type' => 'textfield',
                        'heading' => __( 'Phone number' ),
                        'default' => __( 'Phone number' ),
                    ),
                )
            )
            /*'debug' => array(
                'type'       => 'checkbox',
                'heading'    => 'Show debug',
                'default' => '',
            ),*/
        ),
    ));
}
add_action('ux_builder_setup', 'adminz_map_item');
function adminz_map_item(){
    add_ux_builder_shortcode('adminz_map-item', array(
        'name'      => __('Map marker'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'map' . '.svg',
        'info'      => '{{ title }}',
        'hidden' => true,
        'options' => array(
            'image' => array(
                'type'       => 'image',
                'heading'    => 'Item thumbnail',
                'default' => '',
            ),
            'marker' => array(
                'type'       => 'image',
                'heading'    => 'Item marker',
                'default' => '',
                'description'=> 'Default size: 25x41px | Shadow inherited from parent map'
            ),
            'title' => array(
                'type' => 'textfield',
                'heading' => __( 'Title' ),
            ),
            'address' => array(
                'type' => 'textfield',
                'heading' => __( 'Address' ),
                'default' => __( '' ), 
            ),
            'phone_number'=>array(
                'type' => 'textfield',
                'heading' => __( 'Phone number' ),
                'default' => __( '' ), 
            ),
            'description'=>array(
                'type' => 'textarea',
                'heading' => __( 'More descriptions' ),
                'default' => __( '' ), 
            ),
            'address_opt_1' => array(
                'type' => 'textfield',
                'heading' => __( 'Filter option 1' ),
                'default' => __( '' ),                
            ),
            'address_opt_2' => array(
                'type' => 'textfield',
                'heading' => __( 'Filter option 2' ),
                'default' => __( '' ),                
            ),
            'latlong' => array(
                'type' => 'textfield',
                'heading' => __( 'Lat Long' ),
            ),
            'marker_link'=> array(
                'type' => 'textfield',
                'heading' => __( 'Link to map' ),
            ),
            'popup' => array(
                'type'       => 'checkbox',
                'heading'    => 'Open popup',
                'default' => '',
            ),
            'flyto' => array(
                'type'       => 'checkbox',
                'heading'    => 'Fly to',
                'default' => '',
            ),
        ),
    ));
}
add_shortcode( 'adminz_map-item','adminz_map_item_shortcode');
function adminz_map_function($atts, $content = null ) {   
    extract(shortcode_atts(array(
        'id'=> rand(),
        'layout'=> '1',
        'mapzoom'    => '5',
        'mapheight' => '500',
        'mapstyle'=> 'OpenStreetMap_Mapnik',
        'maptilelayer'=> '',
        'search_form' => 'true',
        'list_items'=> 'true',
        'list_items_title'=> '',
        'item_col'=>1,
        'item_col_mobile'=>1,
        'item_hover'=>'true',
        'alltext'=> 'All',
        'field1'=> 'Country',
        'field2'=> 'City',
        'searchbutton'=> 'Search',
        'markerzoom'=> '9',
        'markericon'=>'',
        'markericonshadow'=>'',
        'debug' => '',
        'view_map'=> 'true',
        'address_text'=> 'Address',
        'phone_number_text'=> 'Phone number'
    ), $atts));    

    $adminz_marker_items = [];
    $content = str_replace(["<div>","</div>"],["",""],$content);

    $jsoncode = do_shortcode( $content);    
    if(preg_match_all('/{(.*)}/', $jsoncode, $matches)){
        $matches= $matches[0];
        if(!empty($matches) and is_array($matches)){
            foreach ($matches as $key => $value) {
                $adminz_marker_items[] = (array) json_decode( $value);
            }
        }
    }  


    $address1_arr = [];
    $address2_arr = [];
    $lat_center = 0;
    $long_center = 0;
    $count = count($adminz_marker_items);    
    $itemslisthtml = '';
    $markerjshtml = '';
    $clickitemhtml = '';    
    if(!empty($adminz_marker_items) and is_array($adminz_marker_items)){
        foreach ($adminz_marker_items as $key => $value) {            
            if(!isset($value['title'])){$value['title'] = ""; }
            if(!isset($value['address'])){$value['address'] = ""; }
            if(!isset($value['phone_number'])){$value['phone_number'] = ""; }            
            if(!isset($value['description'])){$value['description'] = ""; }

            if(!isset($value['address_opt_1'])){$value['address_opt_1'] = ""; }
            if(!isset($value['address_opt_2'])){$value['address_opt_2'] = ""; }

            if(!isset($value['latlong'])){$value['latlong'] = "0,0"; }
            if(!isset($value['marker_link'])){$value['marker_link'] = ""; }



            $value['address'] = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '</br>', $value['address']);
            $value['description'] = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '</br>', $value['description']);

            $marker_content = "";
            if($value['address']){
                $marker_content .= "<div><strong>".$address_text."</strong>: ". $value['address']."</div>";
            }
            if($value['phone_number']){
                $marker_content .= "<div><strong>".$phone_number_text."</strong>: ". $value['phone_number']."</div>";
            }
            if($value['description']){
                $marker_content .= "<div>".$value['description']."</div>";
            }


            $address1_arr[] = $value['address_opt_1'];            
            $address2_arr[$value['address_opt_1']][] = $value['address_opt_2'];

            

            $latlong = explode(",",$value['latlong']);

            if($latlong[0] and $latlong[1]){
                $lat_center += $latlong[0];
                $long_center += $latlong[1];
            }
            if($view_map == true){
                $marker_link = $value['marker_link']? $value['marker_link'] : "https://www.google.com/maps/place/".urlencode($value['latlong'])."/@".$value['latlong'].",".$markerzoom."z";
                $view_map = "<div><a target='_blank' href='".$marker_link."'><small>". _x("View Location",'menu locations')."</small></a></div>";
            }else{
                $view_map = "";
            }
            
            ob_start();
            ?>
            <div 
            data-marker="<?php echo $key; ?>" 
            data-title="<?php echo $value['title']; ?>"
            data-address="<?php echo $value['address']; ?>"
            data-address_opt_1="<?php echo $value['address_opt_1']; ?>"
            data-address_opt_2="<?php echo $value['address_opt_2']; ?>"
            data-latlong="[<?php echo $value['latlong']; ?>]"
            class="adminz_marker_list_item <?php if($item_hover == "true") echo  "hover"; ?> col large-<?php echo 12/$item_col;?> small-<?php echo 12/$item_col_mobile;?>"
            >
                <div class="col-inner">
                    <div style="display: flex; align-items: flex-start;">
                        <?php            
                        if(isset($value['image'])){
                            echo wp_get_attachment_image( $value['image'], 'thumbnail',"",['class'=>"img"] ); 
                        }
                        ?>
                        <div>
                            <div><strong><?php echo $value['title'];?></strong></div>
                            <small><?php echo $marker_content; ?></small>
                            <?php echo $view_map; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $itemslisthtml .= ob_get_clean();

            ob_start();
            ?>
            var image<?php echo $key; ?> = '';
            <?php if(isset($value['image'])){ ?>              
                image<?php echo $key; ?> = '<div style="margin-bottom: 15px; padding-top: 60px; width: 60px; background-size: contain; background-repeat: no-repeat; margin-right:  15px; min-width: 60px;background-image: url(<?php echo wp_get_attachment_image_url($value['image'],array(60,60));?>);"></div>';
                <?php                
            }
            ?>
            
            var popuphtml<?php echo $key; ?> = "<div style='display: flex; align-items: flex-start;'>"+image<?php echo $key; ?>+"<div><strong><?php echo $value['title']; ?></strong><?php echo $marker_content;?><?php echo $view_map;?></div></div>";
            var icon<?php echo $key; ?> = {};

            <?php 

            if(isset($value['marker'])){
                $get_markericon = $value['marker'];
            }else{
                $get_markericon = $markericon;
            }
            if($get_markericon and $markericonshadow){
                $iconsrc = wp_get_attachment_image_src( $get_markericon );
                $iconshadowsrc = wp_get_attachment_image_src( $markericonshadow );
                ?>
                icon<?php echo $key; ?> = {
                    icon: L.icon({
                        iconUrl: "<?php echo $iconsrc[0];?>",
                        iconSize:  [<?php echo $iconsrc[1];?>, <?php echo $iconsrc[2];?>],
                        iconAnchor:   [<?php echo $iconsrc[1]/2;?>, <?php echo $iconsrc[2];?>],
                        shadowUrl: "<?php echo $iconshadowsrc[0];?>", 
                        shadowSize:   [<?php echo $iconshadowsrc[1];?>, <?php echo $iconshadowsrc[2];?>],  
                        shadowAnchor: [<?php echo $iconsrc[1]/2;?>, <?php echo $iconshadowsrc[2];?>],  
                        popupAnchor:  [0, -<?php echo $iconsrc[2];?>]
                    })
                };
                <?php                
            }            
            ?>
            var marker<?php echo $key ;?> = L.marker(
                    [<?php echo $value['latlong'];?>],
                    icon<?php echo $key ;?>
                )
                .addTo(mymap)
                .on("click", markerOnClick)
                .bindPopup(popuphtml<?php echo $key ;?>,{minWidth: 200});

            <?php
            if(isset($value['popup']) and $value['popup'] == "true") {
                ?>
                marker<?php echo $key; ?>.openPopup();
                <?php
            }
            if(isset($value['flyto']) and $value['flyto'] == "true") {
                ?>                
                mymap.setView(marker<?php echo $key; ?>._latlng,<?php echo $markerzoom;?>);
                <?php
            }

            $markerjshtml.= ob_get_clean();            
            
            


            $clickitemhtml.= 'if(index == "'.$key.'"){
                marker'.$key.'.openPopup();
                mymap.setView(marker'.$key.'._latlng,'.$markerzoom.');
            }';
        }        
    }
    if($count){
        $lat_center = $lat_center/$count;
        $long_center = $long_center/$count;
    }
    
    ob_start();
    ?>
    <div class="col large-4 nopaddingbottom">
        <?php echo $field1; ?>
        <select class="address_opt1">
            <option value='All'><?php echo $alltext; ?></option>
            <?php 
            $address1_arr = array_unique($address1_arr);
            foreach ($address1_arr as $key => $value) {
                echo '<option value="'.$value.'">'.$value.'</option>';
            }
            ?>
        </select>
    </div>
    <div class="col large-4 nopaddingbottom">
        <?php echo $field2; ?>
        <?php 
        $defaultopt1 = "";
        if(isset($address1_arr[0])) {$defaultopt1 = $address1_arr[0];}                    
        ?>
        <select class="address_opt2">
            <option data-opt1='All' value='All'><?php echo $alltext; ?></option>
            <?php                 
            foreach ($address2_arr as $key => $value) {
                if(!empty($value) and is_array($value)){                        
                    $value = array_unique($value);                        
                    foreach ($value as $key2 => $option) {
                        echo '<option data-opt1="'.$key.'" value="'.$option.'">'.$option.'</option>';
                    }
                }
            }
            ?>
        </select>
    </div>
    <div class="col large-4 nopaddingbottom">
        <span style="visibility: hidden; display: block;">_</span>
        <button class="button primary address_opt_search expand"><?php echo $searchbutton; ?></button>
    </div>
    <?php 
    $form_html = ob_get_clean();

    if($search_form !== "true"){$form_html = ""; }  

    $col_map = 8;
    $col_items = 4;  
    if($list_items !== "true"){
        $itemslisthtml = ""; 
        $col_map = 12;
    }    


    $map_html = '<div id="adminz_map'.$id.'"></div>';
    
    ob_start();



    if(isset( $_POST['ux_builder_action'] )){
        $map_html = '<div style="background: #71cedf; height: '.$mapheight.'px; border: 2px dashed #000; display: flex; padding: 20px; color: white; justify-content: center; align-items: center; font-size: 1.5em; "> Map </div>';   
        $itemslisthtml = $content;
    }else{
        if($debug){ ?> <style type="text/css"> .leaflet-marker-icon{border: 1px solid gray; } .leaflet-marker-shadow{border: 1px solid red; } </style> <?php }; ?>
        <link rel="stylesheet" href="<?php echo plugin_dir_url(ADMINZ_BASENAME).'assets/leaflet/leaflet.css'; ?>" />
        <script src="<?php echo plugin_dir_url(ADMINZ_BASENAME).'assets/leaflet/leaflet.js'; ?>" defer  id="adminz_leaflet-js"></script>
        <script src="<?php echo plugin_dir_url(ADMINZ_BASENAME).'assets/leaflet/leaflet-providers.js'; ?>" defer  id="adminz_leaflet-providers-js"></script>
        <?php
    }
    ?>
    <style type="text/css"> .adminz_map_layout2{position: relative; } @media(min-width: 850px){.adminz_map_layout2 .list-absolute{position: absolute; z-index: 1; top: 0; right: 0; <?php if($itemslisthtml){echo 'background-color: white;'; }?> height: 100%; } .dark .adminz_map_layout2 .list-absolute{background-color: #232323; } } .adminz_map_layout2 .rightinner{padding: 15px; } #adminz_map<?php echo $id;?> {width: 100%; height: <?php echo $mapheight; ?>px; max-width: 100%; z-index: 1; } .adminz_marker_list_item{display: flex; align-items: flex-start; cursor: pointer; padding-bottom: 15px; } .adminz_marker_list_item.hover .col-inner{padding:  10px; border: 1px solid #8a8a8a29; height: 100%; } .adminz_marker_list_item.hover:hover .col-inner, .adminz_marker_list_item.active .col-inner{background-color:  #8a8a8a29; } .adminz_marker_list_item .img{max-width: 20%; margin-right: 15px; } @media (min-width: 850px){.adminz_map_layout2 .rightinner, .adminz_map_layout1 .adminz_marker_list{max-height: <?php echo $mapheight ?>px; overflow-y: auto; } }
    </style> 
    <?php
    switch ($layout) {
        case '1':
            // container top search
            ?>
            <div class="adminz_map_layout1 row row-full-width">
                
                <?php echo $form_html; ?>
                <div class="col large-<?php echo $col_map; ?>">
                    <?php echo $map_html ;?>
                </div>
                <?php if($itemslisthtml){ ?>
                    <div class="col large-4">
                        <div class="adminz_marker_list row align-equal">
                            <?php echo $itemslisthtml;  ?>
                        </div>   
                    </div>
                <?php } ?>
            </div>
            <?php
            break;
        case '2':
            // fullwidth collapse 
            ?>
            <div class="adminz_map_layout2 row row-full-width row-collapse">                
                <div class="col large-9">
                    <?php echo $map_html ;?>
                </div>
                <?php if($form_html or $itemslisthtml){ ?>
                    <div class="col large-3 small-12 list-absolute">
                        <div class="rightinner">                            
                            <?php if($form_html) { ?>
                                <div class="row">
                                    <?php echo $form_html; ?>
                                </div>
                            <?php } ?>                            
                            <?php if($itemslisthtml) { ?>                                
                                <div class="adminz_marker_list row align-equal">                        
                                    <?php echo $itemslisthtml;  ?>
                                </div> 
                            <?php } ?> 
                        </div>                             
                    </div>
                <?php } ?>
            </div>
            <?php
            break;
        default:
            ?>
            <?php echo $map_html ;?>
            <?php echo do_shortcode('[gap]'); ?>
            <div class="row row-full-width">
                <?php if($form_html or $itemslisthtml){ ?>
                    <?php if($list_items_title){ ?>
                        <div class="col small-12 nopaddingbottom">
                            <?php echo "<h3>".$list_items_title."</h3>"; ?>
                        </div>
                    <?php }?>
                    <div class="col small-12 large-12">
                        <?php if($form_html) { ?>
                            <div class="row">
                                <?php echo $form_html; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col large-12 small-12">
                        <?php if($itemslisthtml) { ?>
                            <div class=" row align-equal adminz_marker_list row-full-width">                        
                                <?php echo $itemslisthtml;  ?>
                            </div> 
                        <?php } ?>                            
                    </div>
                <?php } ?>
            </div>
            <?php
            break;
    }
    
    
    $mainhtml = ob_get_clean();
    ob_start();
    if(!isset($_POST['ux_builder_action'])){
        ?>        
        <script type="text/javascript">
            window.addEventListener('DOMContentLoaded', function() {
                (function($){
                    var adminz_marker_items = []; 
                    <?php echo 'var lat_center = '.$lat_center.";" ; echo 'var long_center = '.$long_center.";" ; ?>
                    var mymap = L.map('adminz_map<?php echo $id;?>',{scrollWheelZoom: false }).setView([lat_center, long_center], <?php echo $mapzoom;?> );
                    <?php if($mapstyle !=='default'){ 
                    $mapstyles = ["OpenStreetMap_Mapnik" => "'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {  maxZoom: 19,    attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'}", "OpenStreetMap_DE" => "'https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {   maxZoom: 18,    attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'}", "OpenStreetMap_CH" => "'https://tile.osm.ch/switzerland/{z}/{x}/{y}.png', { maxZoom: 18,    attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   bounds: [[45, 5], [48, 11]]}", "OpenStreetMap_France" => "'https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', { maxZoom: 20,    attribution: '&copy; OpenStreetMap France | &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'}", "OpenStreetMap_HOT" => "'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {  maxZoom: 19,    attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors, Tiles style by <a href=\"https://www.hotosm.org/\" target=\"_blank\">Humanitarian OpenStreetMap Team</a> hosted by <a href=\"https://openstreetmap.fr/\" target=\"_blank\">OpenStreetMap France</a>'}", "OpenStreetMap_BZH" => "'https://tile.openstreetmap.bzh/br/{z}/{x}/{y}.png', {  maxZoom: 19,    attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors, Tiles courtesy of <a href=\"http://www.openstreetmap.bzh/\" target=\"_blank\">Breton OpenStreetMap Team</a>',  bounds: [[46.2, -5.5], [50, 0.7]]}", "OpenTopoMap" => "'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { maxZoom: 17,    attribution: 'Map data: &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors, <a href=\"http://viewfinderpanoramas.org\">SRTM</a> | Map style: &copy; <a href=\"https://opentopomap.org\">OpenTopoMap</a> (<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY-SA</a>)'}", "Stadia_AlidadeSmooth" => "'https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.png', {    maxZoom: 20,    attribution: '&copy; <a href=\"https://stadiamaps.com/\">Stadia Maps</a>, &copy; <a href=\"https://openmaptiles.org/\">OpenMapTiles</a> &copy; <a href=\"http://openstreetmap.org\">OpenStreetMap</a> contributors'}", "Stadia_AlidadeSmoothDark" => "'https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png', {   maxZoom: 20,    attribution: '&copy; <a href=\"https://stadiamaps.com/\">Stadia Maps</a>, &copy; <a href=\"https://openmaptiles.org/\">OpenMapTiles</a> &copy; <a href=\"http://openstreetmap.org\">OpenStreetMap</a> contributors'}", "Stadia_OSMBright" => "'https://tiles.stadiamaps.com/tiles/osm_bright/{z}/{x}/{y}{r}.png', {    maxZoom: 20,    attribution: '&copy; <a href=\"https://stadiamaps.com/\">Stadia Maps</a>, &copy; <a href=\"https://openmaptiles.org/\">OpenMapTiles</a> &copy; <a href=\"http://openstreetmap.org\">OpenStreetMap</a> contributors'}", "Stadia_Outdoors" => "'https://tiles.stadiamaps.com/tiles/outdoors/{z}/{x}/{y}{r}.png', {   maxZoom: 20,    attribution: '&copy; <a href=\"https://stadiamaps.com/\">Stadia Maps</a>, &copy; <a href=\"https://openmaptiles.org/\">OpenMapTiles</a> &copy; <a href=\"http://openstreetmap.org\">OpenStreetMap</a> contributors'}", "CyclOSM" => "'https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', {    maxZoom: 20,    attribution: '<a href=\"https://github.com/cyclosm/cyclosm-cartocss-style/releases\" title=\"CyclOSM - Open Bicycle render\">CyclOSM</a> | Map data: &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'}", "Hydda_Full" => "'https://{s}.tile.openstreetmap.se/hydda/full/{z}/{x}/{y}.png', {  maxZoom: 20,    attribution: 'Tiles courtesy of <a href=\"http://openstreetmap.se/\" target=\"_blank\">OpenStreetMap Sweden</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'}", "Hydda_Base" => "'https://{s}.tile.openstreetmap.se/hydda/base/{z}/{x}/{y}.png', {  maxZoom: 20,    attribution: 'Tiles courtesy of <a href=\"http://openstreetmap.se/\" target=\"_blank\">OpenStreetMap Sweden</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'}", "Stamen_Toner" => "'https://stamen-tiles-{s}.a.ssl.fastly.net/toner/{z}/{x}/{y}{r}.{ext}', {    attribution: 'Map tiles by <a href=\"http://stamen.com\">Stamen Design</a>, <a href=\"http://creativecommons.org/licenses/by/3.0\">CC BY 3.0</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   subdomains: 'abcd', minZoom: 0, maxZoom: 20,    ext: 'png'}", "Stamen_TonerBackground" => "'https://stamen-tiles-{s}.a.ssl.fastly.net/toner-background/{z}/{x}/{y}{r}.{ext}', {   attribution: 'Map tiles by <a href=\"http://stamen.com\">Stamen Design</a>, <a href=\"http://creativecommons.org/licenses/by/3.0\">CC BY 3.0</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   subdomains: 'abcd', minZoom: 0, maxZoom: 20,    ext: 'png'}", "Stamen_TonerLite" => "'https://stamen-tiles-{s}.a.ssl.fastly.net/toner-lite/{z}/{x}/{y}{r}.{ext}', {   attribution: 'Map tiles by <a href=\"http://stamen.com\">Stamen Design</a>, <a href=\"http://creativecommons.org/licenses/by/3.0\">CC BY 3.0</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   subdomains: 'abcd', minZoom: 0, maxZoom: 20,    ext: 'png'}", "Stamen_Watercolor" => "'https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.{ext}', { attribution: 'Map tiles by <a href=\"http://stamen.com\">Stamen Design</a>, <a href=\"http://creativecommons.org/licenses/by/3.0\">CC BY 3.0</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   subdomains: 'abcd', minZoom: 1, maxZoom: 16,    ext: 'jpg'}", "Stamen_Terrain" => "'https://stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}{r}.{ext}', {    attribution: 'Map tiles by <a href=\"http://stamen.com\">Stamen Design</a>, <a href=\"http://creativecommons.org/licenses/by/3.0\">CC BY 3.0</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   subdomains: 'abcd', minZoom: 0, maxZoom: 18,    ext: 'png'}", "Stamen_TerrainBackground" => "'https://stamen-tiles-{s}.a.ssl.fastly.net/terrain-background/{z}/{x}/{y}{r}.{ext}', {   attribution: 'Map tiles by <a href=\"http://stamen.com\">Stamen Design</a>, <a href=\"http://creativecommons.org/licenses/by/3.0\">CC BY 3.0</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   subdomains: 'abcd', minZoom: 0, maxZoom: 18,    ext: 'png'}", "Stamen_TerrainLabels" => "'https://stamen-tiles-{s}.a.ssl.fastly.net/terrain-labels/{z}/{x}/{y}{r}.{ext}', {   attribution: 'Map tiles by <a href=\"http://stamen.com\">Stamen Design</a>, <a href=\"http://creativecommons.org/licenses/by/3.0\">CC BY 3.0</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   subdomains: 'abcd', minZoom: 0, maxZoom: 18,    ext: 'png'}", "Stamen_TopOSMRelief" => "'https://stamen-tiles-{s}.a.ssl.fastly.net/toposm-color-relief/{z}/{x}/{y}.{ext}', {  attribution: 'Map tiles by <a href=\"http://stamen.com\">Stamen Design</a>, <a href=\"http://creativecommons.org/licenses/by/3.0\">CC BY 3.0</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   subdomains: 'abcd', minZoom: 0, maxZoom: 20,    ext: 'jpg', bounds: [[22, -132], [51, -56]]}", "Thunderforest_OpenCycleMap" => "'https://{s}.tile.thunderforest.com/cycle/{z}/{x}/{y}.png?apikey={apikey}', {  attribution: '&copy; <a href=\"http://www.thunderforest.com/\">Thunderforest</a>, &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   apikey: '<your apikey>',    maxZoom: 22}", "Thunderforest_Transport" => "'https://{s}.tile.thunderforest.com/transport/{z}/{x}/{y}.png?apikey={apikey}', { attribution: '&copy; <a href=\"http://www.thunderforest.com/\">Thunderforest</a>, &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   apikey: '<your apikey>',    maxZoom: 22}", "Thunderforest_TransportDark" => "'https://{s}.tile.thunderforest.com/transport-dark/{z}/{x}/{y}.png?apikey={apikey}', {    attribution: '&copy; <a href=\"http://www.thunderforest.com/\">Thunderforest</a>, &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   apikey: '<your apikey>',    maxZoom: 22}", "Thunderforest_SpinalMap" => "'https://{s}.tile.thunderforest.com/spinal-map/{z}/{x}/{y}.png?apikey={apikey}', {    attribution: '&copy; <a href=\"http://www.thunderforest.com/\">Thunderforest</a>, &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   apikey: '<your apikey>',    maxZoom: 22}", "Thunderforest_Landscape" => "'https://{s}.tile.thunderforest.com/landscape/{z}/{x}/{y}.png?apikey={apikey}', { attribution: '&copy; <a href=\"http://www.thunderforest.com/\">Thunderforest</a>, &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   apikey: '<your apikey>',    maxZoom: 22}", "Thunderforest_Outdoors" => "'https://{s}.tile.thunderforest.com/outdoors/{z}/{x}/{y}.png?apikey={apikey}', {   attribution: '&copy; <a href=\"http://www.thunderforest.com/\">Thunderforest</a>, &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   apikey: '<your apikey>',    maxZoom: 22}", "Thunderforest_Pioneer" => "'https://{s}.tile.thunderforest.com/pioneer/{z}/{x}/{y}.png?apikey={apikey}', { attribution: '&copy; <a href=\"http://www.thunderforest.com/\">Thunderforest</a>, &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   apikey: '<your apikey>',    maxZoom: 22}", "Thunderforest_MobileAtlas" => "'https://{s}.tile.thunderforest.com/mobile-atlas/{z}/{x}/{y}.png?apikey={apikey}', {    attribution: '&copy; <a href=\"http://www.thunderforest.com/\">Thunderforest</a>, &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   apikey: '<your apikey>',    maxZoom: 22}", "Thunderforest_Neighbourhood" => "'https://{s}.tile.thunderforest.com/neighbourhood/{z}/{x}/{y}.png?apikey={apikey}', { attribution: '&copy; <a href=\"http://www.thunderforest.com/\">Thunderforest</a>, &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   apikey: '<your apikey>',    maxZoom: 22}", "Jawg_Streets" => "'https://{s}.tile.jawg.io/jawg-streets/{z}/{x}/{y}{r}.png?access-token={accessToken}', { attribution: '<a href=\"http://jawg.io\" title=\"Tiles Courtesy of Jawg Maps\" target=\"_blank\">&copy; <b>Jawg</b>Maps</a> &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors', minZoom: 0, maxZoom: 22,    subdomains: 'abcd', accessToken: '<your accessToken>'}", "Jawg_Terrain" => "'https://{s}.tile.jawg.io/jawg-terrain/{z}/{x}/{y}{r}.png?access-token={accessToken}', { attribution: '<a href=\"http://jawg.io\" title=\"Tiles Courtesy of Jawg Maps\" target=\"_blank\">&copy; <b>Jawg</b>Maps</a> &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors', minZoom: 0, maxZoom: 22,    subdomains: 'abcd', accessToken: '<your accessToken>'}", "Jawg_Sunny" => "'https://{s}.tile.jawg.io/jawg-sunny/{z}/{x}/{y}{r}.png?access-token={accessToken}', { attribution: '<a href=\"http://jawg.io\" title=\"Tiles Courtesy of Jawg Maps\" target=\"_blank\">&copy; <b>Jawg</b>Maps</a> &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors', minZoom: 0, maxZoom: 22,    subdomains: 'abcd', accessToken: '<your accessToken>'}", "Jawg_Dark" => "'https://{s}.tile.jawg.io/jawg-dark/{z}/{x}/{y}{r}.png?access-token={accessToken}', {   attribution: '<a href=\"http://jawg.io\" title=\"Tiles Courtesy of Jawg Maps\" target=\"_blank\">&copy; <b>Jawg</b>Maps</a> &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors', minZoom: 0, maxZoom: 22,    subdomains: 'abcd', accessToken: '<your accessToken>'}", "Jawg_Light" => "'https://{s}.tile.jawg.io/jawg-light/{z}/{x}/{y}{r}.png?access-token={accessToken}', { attribution: '<a href=\"http://jawg.io\" title=\"Tiles Courtesy of Jawg Maps\" target=\"_blank\">&copy; <b>Jawg</b>Maps</a> &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors', minZoom: 0, maxZoom: 22,    subdomains: 'abcd', accessToken: '<your accessToken>'}", "Jawg_Matrix" => "'https://{s}.tile.jawg.io/jawg-matrix/{z}/{x}/{y}{r}.png?access-token={accessToken}', {   attribution: '<a href=\"http://jawg.io\" title=\"Tiles Courtesy of Jawg Maps\" target=\"_blank\">&copy; <b>Jawg</b>Maps</a> &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors', minZoom: 0, maxZoom: 22,    subdomains: 'abcd', accessToken: '<your accessToken>'}", "TomTom_Basic" => "'https://{s}.api.tomtom.com/map/1/tile/basic/{style}/{z}/{x}/{y}.{ext}?key={apikey}', {  maxZoom: 22,    attribution: '<a href=\"https://tomtom.com\" target=\"_blank\">&copy;  1992 - 2021 TomTom.</a> ',   subdomains: 'abcd', style: 'main',  ext: 'png', apikey: '<insert your API key here>'}", "TomTom_Hybrid" => "'https://{s}.api.tomtom.com/map/1/tile/hybrid/{style}/{z}/{x}/{y}.{ext}?key={apikey}', {    maxZoom: 22,    attribution: '<a href=\"https://tomtom.com\" target=\"_blank\">&copy;  1992 - 2021 TomTom.</a> ',   subdomains: 'abcd', style: 'main',  ext: 'png', apikey: '<insert your API key here>'}", "TomTom_Labels" => "'https://{s}.api.tomtom.com/map/1/tile/labels/{style}/{z}/{x}/{y}.{ext}?key={apikey}', {    maxZoom: 22,    attribution: '<a href=\"https://tomtom.com\" target=\"_blank\">&copy;  1992 - 2021 TomTom.</a> ',   subdomains: 'abcd', style: 'main',  ext: 'png', apikey: '<insert your API key here>'}", "Esri_WorldStreetMap" => "'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, 2012'}", "Esri_DeLorme" => "'https://server.arcgisonline.com/ArcGIS/rest/services/Specialty/DeLorme_World_Base_Map/MapServer/tile/{z}/{y}/{x}', {    attribution: 'Tiles &copy; Esri &mdash; Copyright: &copy;2012 DeLorme', minZoom: 1, maxZoom: 11}", "Esri_WorldTopoMap" => "'https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ, TomTom, Intermap, iPC, USGS, FAO, NPS, NRCAN, GeoBase, Kadaster NL, Ordnance Survey, Esri Japan, METI, Esri China (Hong Kong), and the GIS User Community'}", "Esri_WorldImagery" => "'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {  attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'}", "Esri_WorldTerrain" => "'https://server.arcgisonline.com/ArcGIS/rest/services/World_Terrain_Base/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri &mdash; Source: USGS, Esri, TANA, DeLorme, and NPS',    maxZoom: 13}", "Esri_WorldShadedRelief" => "'https://server.arcgisonline.com/ArcGIS/rest/services/World_Shaded_Relief/MapServer/tile/{z}/{y}/{x}', {   attribution: 'Tiles &copy; Esri &mdash; Source: Esri',  maxZoom: 13}", "Esri_WorldPhysical" => "'https://server.arcgisonline.com/ArcGIS/rest/services/World_Physical_Map/MapServer/tile/{z}/{y}/{x}', {    attribution: 'Tiles &copy; Esri &mdash; Source: US National Park Service',  maxZoom: 8}", "Esri_OceanBasemap" => "'https://server.arcgisonline.com/ArcGIS/rest/services/Ocean_Basemap/MapServer/tile/{z}/{y}/{x}', {  attribution: 'Tiles &copy; Esri &mdash; Sources: GEBCO, NOAA, CHS, OSU, UNH, CSUMB, National Geographic, DeLorme, NAVTEQ, and Esri',    maxZoom: 13}", "Esri_NatGeoWorldMap" => "'https://server.arcgisonline.com/ArcGIS/rest/services/NatGeo_World_Map/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri &mdash; National Geographic, Esri, DeLorme, NAVTEQ, UNEP-WCMC, USGS, NASA, ESA, METI, NRCAN, GEBCO, NOAA, iPC', maxZoom: 16}", "Esri_WorldGrayCanvas" => "'https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}', {    attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ', maxZoom: 16}", "MtbMap" => "'http://tile.mtbmap.cz/mtbmap_tiles/{z}/{x}/{y}.png', {    attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors &amp; USGS'}", "CartoDB_Positron" => "'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {  attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors &copy; <a href=\"https://carto.com/attributions\">CARTO</a>',   subdomains: 'abcd', maxZoom: 19}", "CartoDB_PositronNoLabels" => "'https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png', { attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors &copy; <a href=\"https://carto.com/attributions\">CARTO</a>',   subdomains: 'abcd', maxZoom: 19}", "CartoDB_PositronOnlyLabels" => "'https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}{r}.png', {    attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors &copy; <a href=\"https://carto.com/attributions\">CARTO</a>',   subdomains: 'abcd', maxZoom: 19}", "CartoDB_DarkMatter" => "'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors &copy; <a href=\"https://carto.com/attributions\">CARTO</a>',   subdomains: 'abcd', maxZoom: 19}", "CartoDB_DarkMatterNoLabels" => "'https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}{r}.png', {    attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors &copy; <a href=\"https://carto.com/attributions\">CARTO</a>',   subdomains: 'abcd', maxZoom: 19}", "CartoDB_DarkMatterOnlyLabels" => "'https://{s}.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}{r}.png', {   attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors &copy; <a href=\"https://carto.com/attributions\">CARTO</a>',   subdomains: 'abcd', maxZoom: 19}", "CartoDB_Voyager" => "'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors &copy; <a href=\"https://carto.com/attributions\">CARTO</a>',   subdomains: 'abcd', maxZoom: 19}", "CartoDB_VoyagerNoLabels" => "'https://{s}.basemaps.cartocdn.com/rastertiles/voyager_nolabels/{z}/{x}/{y}{r}.png', {    attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors &copy; <a href=\"https://carto.com/attributions\">CARTO</a>',   subdomains: 'abcd', maxZoom: 19}", "CartoDB_VoyagerOnlyLabels" => "'https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png', {   attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors &copy; <a href=\"https://carto.com/attributions\">CARTO</a>',   subdomains: 'abcd', maxZoom: 19}", "CartoDB_VoyagerLabelsUnder" => "'https://{s}.basemaps.cartocdn.com/rastertiles/voyager_labels_under/{z}/{x}/{y}{r}.png', { attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors &copy; <a href=\"https://carto.com/attributions\">CARTO</a>',   subdomains: 'abcd', maxZoom: 19}", "HikeBike_HikeBike" => "'https://tiles.wmflabs.org/hikebike/{z}/{x}/{y}.png', { maxZoom: 19,    attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'}", "HikeBike_HillShading" => "'https://tiles.wmflabs.org/hillshading/{z}/{x}/{y}.png', {   maxZoom: 15,    attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'}", "BasemapAT_basemap" => "'https://maps{s}.wien.gv.at/basemap/geolandbasemap/{type}/google3857/{z}/{y}/{x}.{format}', {   maxZoom: 20,    attribution: 'Datenquelle: <a href=\"https://www.basemap.at\">basemap.at</a>',  subdomains: [\"\", \"1\", \"2\", \"3\", \"4\"], type: 'normal', format: 'png',  bounds: [[46.35877, 8.782379], [49.037872, 17.189532]]}", "BasemapAT_grau" => "'https://maps{s}.wien.gv.at/basemap/bmapgrau/{type}/google3857/{z}/{y}/{x}.{format}', {    maxZoom: 19,    attribution: 'Datenquelle: <a href=\"https://www.basemap.at\">basemap.at</a>',  subdomains: [\"\", \"1\", \"2\", \"3\", \"4\"], type: 'normal', format: 'png',  bounds: [[46.35877, 8.782379], [49.037872, 17.189532]]}", "BasemapAT_overlay" => "'https://maps{s}.wien.gv.at/basemap/bmapoverlay/{type}/google3857/{z}/{y}/{x}.{format}', {  maxZoom: 19,    attribution: 'Datenquelle: <a href=\"https://www.basemap.at\">basemap.at</a>',  subdomains: [\"\", \"1\", \"2\", \"3\", \"4\"], type: 'normal', format: 'png',  bounds: [[46.35877, 8.782379], [49.037872, 17.189532]]}", "BasemapAT_terrain" => "'https://maps{s}.wien.gv.at/basemap/bmapgelaende/{type}/google3857/{z}/{y}/{x}.{format}', { maxZoom: 19,    attribution: 'Datenquelle: <a href=\"https://www.basemap.at\">basemap.at</a>',  subdomains: [\"\", \"1\", \"2\", \"3\", \"4\"], type: 'grau',   format: 'jpeg', bounds: [[46.35877, 8.782379], [49.037872, 17.189532]]}", "BasemapAT_surface" => "'https://maps{s}.wien.gv.at/basemap/bmapoberflaeche/{type}/google3857/{z}/{y}/{x}.{format}', {  maxZoom: 19,    attribution: 'Datenquelle: <a href=\"https://www.basemap.at\">basemap.at</a>',  subdomains: [\"\", \"1\", \"2\", \"3\", \"4\"], type: 'grau',   format: 'jpeg', bounds: [[46.35877, 8.782379], [49.037872, 17.189532]]}", "BasemapAT_highdpi" => "'https://maps{s}.wien.gv.at/basemap/bmaphidpi/{type}/google3857/{z}/{y}/{x}.{format}', {    maxZoom: 19,    attribution: 'Datenquelle: <a href=\"https://www.basemap.at\">basemap.at</a>',  subdomains: [\"\", \"1\", \"2\", \"3\", \"4\"], type: 'normal', format: 'jpeg', bounds: [[46.35877, 8.782379], [49.037872, 17.189532]]}", "BasemapAT_orthofoto" => "'https://maps{s}.wien.gv.at/basemap/bmaporthofoto30cm/{type}/google3857/{z}/{y}/{x}.{format}', {  maxZoom: 20,    attribution: 'Datenquelle: <a href=\"https://www.basemap.at\">basemap.at</a>',  subdomains: [\"\", \"1\", \"2\", \"3\", \"4\"], type: 'normal', format: 'jpeg', bounds: [[46.35877, 8.782379], [49.037872, 17.189532]]}", "nlmaps_standaard" => "'https://geodata.nationaalgeoregister.nl/tiles/service/wmts/brtachtergrondkaart/EPSG:3857/{z}/{x}/{y}.png', {    minZoom: 6, maxZoom: 19,    bounds: [[50.5, 3.25], [54, 7.6]],  attribution: 'Kaartgegevens &copy; <a href=\"kadaster.nl\">Kadaster</a>'}", "nlmaps_pastel" => "'https://geodata.nationaalgeoregister.nl/tiles/service/wmts/brtachtergrondkaartpastel/EPSG:3857/{z}/{x}/{y}.png', { minZoom: 6, maxZoom: 19,    bounds: [[50.5, 3.25], [54, 7.6]],  attribution: 'Kaartgegevens &copy; <a href=\"kadaster.nl\">Kadaster</a>'}", "nlmaps_grijs" => "'https://geodata.nationaalgeoregister.nl/tiles/service/wmts/brtachtergrondkaartgrijs/EPSG:3857/{z}/{x}/{y}.png', {   minZoom: 6, maxZoom: 19,    bounds: [[50.5, 3.25], [54, 7.6]],  attribution: 'Kaartgegevens &copy; <a href=\"kadaster.nl\">Kadaster</a>'}", "nlmaps_luchtfoto" => "'https://geodata.nationaalgeoregister.nl/luchtfoto/rgb/wmts/2018_ortho25/EPSG:3857/{z}/{x}/{y}.png', {   minZoom: 6, maxZoom: 19,    bounds: [[50.5, 3.25], [54, 7.6]],  attribution: 'Kaartgegevens &copy; <a href=\"kadaster.nl\">Kadaster</a>'}", "NASAGIBS_ModisTerraTrueColorCR" => "'https://map1.vis.earthdata.nasa.gov/wmts-webmerc/MODIS_Terra_CorrectedReflectance_TrueColor/default/{time}/{tilematrixset}{maxZoom}/{z}/{y}/{x}.{format}', {  attribution: 'Imagery provided by services from the Global Imagery Browse Services (GIBS), operated by the NASA/GSFC/Earth Science Data and Information System (<a href=\"https://earthdata.nasa.gov\">ESDIS</a>) with funding provided by NASA/HQ.',   bounds: [[-85.0511287776, -179.999999975], [85.0511287776, 179.999999975]], minZoom: 1, maxZoom: 9, format: 'jpg',  time: '',   tilematrixset: 'GoogleMapsCompatible_Level'}", "NASAGIBS_ModisTerraBands367CR" => "'https://map1.vis.earthdata.nasa.gov/wmts-webmerc/MODIS_Terra_CorrectedReflectance_Bands367/default/{time}/{tilematrixset}{maxZoom}/{z}/{y}/{x}.{format}', {    attribution: 'Imagery provided by services from the Global Imagery Browse Services (GIBS), operated by the NASA/GSFC/Earth Science Data and Information System (<a href=\"https://earthdata.nasa.gov\">ESDIS</a>) with funding provided by NASA/HQ.',   bounds: [[-85.0511287776, -179.999999975], [85.0511287776, 179.999999975]], minZoom: 1, maxZoom: 9, format: 'jpg',  time: '',   tilematrixset: 'GoogleMapsCompatible_Level'}", "NASAGIBS_ViirsEarthAtNight2012" => "'https://map1.vis.earthdata.nasa.gov/wmts-webmerc/VIIRS_CityLights_2012/default/{time}/{tilematrixset}{maxZoom}/{z}/{y}/{x}.{format}', {   attribution: 'Imagery provided by services from the Global Imagery Browse Services (GIBS), operated by the NASA/GSFC/Earth Science Data and Information System (<a href=\"https://earthdata.nasa.gov\">ESDIS</a>) with funding provided by NASA/HQ.',   bounds: [[-85.0511287776, -179.999999975], [85.0511287776, 179.999999975]], minZoom: 1, maxZoom: 8, format: 'jpg',  time: '',   tilematrixset: 'GoogleMapsCompatible_Level'}", "NLS" => "'https://nls-{s}.tileserver.com/nls/{z}/{x}/{y}.jpg', {   attribution: '<a href=\"http://geo.nls.uk/maps/\">National Library of Scotland Historic Maps</a>',  bounds: [[49.6, -12], [61.7, 3]],   minZoom: 1, maxZoom: 18,    subdomains: '0123'}", "GeoportailFrance_plan" => "'https://wxs.ign.fr/{apikey}/geoportail/wmts?REQUEST=GetTile&SERVICE=WMTS&VERSION=1.0.0&STYLE={style}&TILEMATRIXSET=PM&FORMAT={format}&LAYER=GEOGRAPHICALGRIDSYSTEMS.PLANIGNV2&TILEMATRIX={z}&TILEROW={y}&TILECOL={x}', {   attribution: '<a target=\"_blank\" href=\"https://www.geoportail.gouv.fr/\">Geoportail France</a>', bounds: [[-75, -180], [81, 180]],   minZoom: 2, maxZoom: 18,    apikey: 'choisirgeoportail',    format: 'image/png',    style: 'normal'}", "GeoportailFrance_parcels" => "'https://wxs.ign.fr/{apikey}/geoportail/wmts?REQUEST=GetTile&SERVICE=WMTS&VERSION=1.0.0&STYLE={style}&TILEMATRIXSET=PM&FORMAT={format}&LAYER=CADASTRALPARCELS.PARCELLAIRE_EXPRESS&TILEMATRIX={z}&TILEROW={y}&TILECOL={x}', { attribution: '<a target=\"_blank\" href=\"https://www.geoportail.gouv.fr/\">Geoportail France</a>', bounds: [[-75, -180], [81, 180]],   minZoom: 2, maxZoom: 20,    apikey: 'choisirgeoportail',    format: 'image/png',    style: 'PCI vecteur'}", "GeoportailFrance_orthos" => "'https://wxs.ign.fr/{apikey}/geoportail/wmts?REQUEST=GetTile&SERVICE=WMTS&VERSION=1.0.0&STYLE={style}&TILEMATRIXSET=PM&FORMAT={format}&LAYER=ORTHOIMAGERY.ORTHOPHOTOS&TILEMATRIX={z}&TILEROW={y}&TILECOL={x}', {  attribution: '<a target=\"_blank\" href=\"https://www.geoportail.gouv.fr/\">Geoportail France</a>', bounds: [[-75, -180], [81, 180]],   minZoom: 2, maxZoom: 19,    apikey: 'choisirgeoportail',    format: 'image/jpeg',   style: 'normal'}", "USGS_USTopo" => "'https://basemap.nationalmap.gov/arcgis/rest/services/USGSTopo/MapServer/tile/{z}/{y}/{x}', { maxZoom: 20,    attribution: 'Tiles courtesy of the <a href=\"https://usgs.gov/\">U.S. Geological Survey</a>'}", "USGS_USImagery" => "'https://basemap.nationalmap.gov/arcgis/rest/services/USGSImageryOnly/MapServer/tile/{z}/{y}/{x}', {   maxZoom: 20,    attribution: 'Tiles courtesy of the <a href=\"https://usgs.gov/\">U.S. Geological Survey</a>'}", "USGS_USImageryTopo" => "'https://basemap.nationalmap.gov/arcgis/rest/services/USGSImageryTopo/MapServer/tile/{z}/{y}/{x}', {   maxZoom: 20,    attribution: 'Tiles courtesy of the <a href=\"https://usgs.gov/\">U.S. Geological Survey</a>'}", ];
                        $mapstyle = $mapstyles[$mapstyle];        
                        $mapstyle = str_replace(['<your apikey>','{apikey}'], ["key123456-api","key123456-api"], $mapstyle);        
                        ?>
                        var mapstyle = L.tileLayer(<?php echo $mapstyle; ?>);
                        mapstyle.addTo(mymap);            
                        <?php
                    }else{
                        ?>
                        var mapstyle = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {id: 'mapbox/streets-v11', });
                        mapstyle.addTo(mymap);
                        <?php 
                    }
                    $maptilelayers = ['OpenSeaMap' => "'https://tiles.openseamap.org/seamark/{z}/{x}/{y}.png', {  attribution: 'Map data: &copy; <a href=\"http://www.openseamap.org\">OpenSeaMap</a> contributors'}", 'OpenPtMap' => "'http://openptmap.org/tiles/{z}/{x}/{y}.png', { maxZoom: 17,    attribution: 'Map data: &copy; <a href=\"http://www.openptmap.org\">OpenPtMap</a> contributors'}", 'OpenRailwayMap' => "'https://{s}.tiles.openrailwaymap.org/standard/{z}/{x}/{y}.png', { maxZoom: 19,    attribution: 'Map data: &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors | Map style: &copy; <a href=\"https://www.OpenRailwayMap.org\">OpenRailwayMap</a> (<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY-SA</a>)'}", 'OpenFireMap' => "'http://openfiremap.org/hytiles/{z}/{x}/{y}.png', {   maxZoom: 19,    attribution: 'Map data: &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors | Map style: &copy; <a href=\"http://www.openfiremap.org\">OpenFireMap</a> (<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY-SA</a>)'}", 'SafeCast' => "'https://s3.amazonaws.com/te512.safecast.org/{z}/{x}/{y}.png', { maxZoom: 16,    attribution: 'Map data: &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors | Map style: &copy; <a href=\"https://blog.safecast.org/about/\">SafeCast</a> (<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY-SA</a>)'}", 'Hydda_RoadsAndLabels' => "'https://{s}.tile.openstreetmap.se/hydda/roads_and_labels/{z}/{x}/{y}.png', {    maxZoom: 20,    attribution: 'Tiles courtesy of <a href=\"http://openstreetmap.se/\" target=\"_blank\">OpenStreetMap Sweden</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'}", 'Stamen_TonerHybrid' => "'https://stamen-tiles-{s}.a.ssl.fastly.net/toner-hybrid/{z}/{x}/{y}{r}.{ext}', {   attribution: 'Map tiles by <a href=\"http://stamen.com\">Stamen Design</a>, <a href=\"http://creativecommons.org/licenses/by/3.0\">CC BY 3.0</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   subdomains: 'abcd', minZoom: 0, maxZoom: 20,    ext: 'png'}", 'Stamen_TonerLines' => "'https://stamen-tiles-{s}.a.ssl.fastly.net/toner-lines/{z}/{x}/{y}{r}.{ext}', { attribution: 'Map tiles by <a href=\"http://stamen.com\">Stamen Design</a>, <a href=\"http://creativecommons.org/licenses/by/3.0\">CC BY 3.0</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   subdomains: 'abcd', minZoom: 0, maxZoom: 20,    ext: 'png'}", 'Stamen_TonerLabels' => "'https://stamen-tiles-{s}.a.ssl.fastly.net/toner-labels/{z}/{x}/{y}{r}.{ext}', {   attribution: 'Map tiles by <a href=\"http://stamen.com\">Stamen Design</a>, <a href=\"http://creativecommons.org/licenses/by/3.0\">CC BY 3.0</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   subdomains: 'abcd', minZoom: 0, maxZoom: 20,    ext: 'png'}", 'Stamen_TopOSMFeatures' => "'https://stamen-tiles-{s}.a.ssl.fastly.net/toposm-features/{z}/{x}/{y}{r}.{ext}', { attribution: 'Map tiles by <a href=\"http://stamen.com\">Stamen Design</a>, <a href=\"http://creativecommons.org/licenses/by/3.0\">CC BY 3.0</a> &mdash; Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',   subdomains: 'abcd', minZoom: 0, maxZoom: 20,    ext: 'png', bounds: [[22, -132], [51, -56]],    opacity: 0.9}", 'OpenWeatherMap_Clouds' => "'http://{s}.tile.openweathermap.org/map/clouds/{z}/{x}/{y}.png?appid={apiKey}', {   maxZoom: 19,    attribution: 'Map data &copy; <a href=\"http://openweathermap.org\">OpenWeatherMap</a>',    apiKey: '<insert your api key here>',   opacity: 0.5}", 'OpenWeatherMap_Pressure' => "'http://{s}.tile.openweathermap.org/map/pressure/{z}/{x}/{y}.png?appid={apiKey}', {   maxZoom: 19,    attribution: 'Map data &copy; <a href=\"http://openweathermap.org\">OpenWeatherMap</a>',    apiKey: '<insert your api key here>',   opacity: 0.5}", 'OpenWeatherMap_Wind' => "'http://{s}.tile.openweathermap.org/map/wind/{z}/{x}/{y}.png?appid={apiKey}', {   maxZoom: 19,    attribution: 'Map data &copy; <a href=\"http://openweathermap.org\">OpenWeatherMap</a>',    apiKey: '<insert your api key here>',   opacity: 0.5}", 'NASAGIBS_ModisTerraSnowCover' => "'https://map1.vis.earthdata.nasa.gov/wmts-webmerc/MODIS_Terra_Snow_Cover/default/{time}/{tilematrixset}{maxZoom}/{z}/{y}/{x}.{format}', {    attribution: 'Imagery provided by services from the Global Imagery Browse Services (GIBS), operated by the NASA/GSFC/Earth Science Data and Information System (<a href=\"https://earthdata.nasa.gov\">ESDIS</a>) with funding provided by NASA/HQ.',   bounds: [[-85.0511287776, -179.999999975], [85.0511287776, 179.999999975]], minZoom: 1, maxZoom: 8, format: 'png',  time: '',   tilematrixset: 'GoogleMapsCompatible_Level',    opacity: 0.75}", 'JusticeMap_income' => "'http://www.justicemap.org/tile/{size}/income/{z}/{x}/{y}.png', {   attribution: '<a href=\"http://www.justicemap.org/terms.php\">Justice Map</a>', size: 'county', bounds: [[14, -180], [72, -56]]}", 'JusticeMap_americanIndian' => "'http://www.justicemap.org/tile/{size}/indian/{z}/{x}/{y}.png', {   attribution: '<a href=\"http://www.justicemap.org/terms.php\">Justice Map</a>', size: 'county', bounds: [[14, -180], [72, -56]]}", 'JusticeMap_asian' => "'http://www.justicemap.org/tile/{size}/asian/{z}/{x}/{y}.png', { attribution: '<a href=\"http://www.justicemap.org/terms.php\">Justice Map</a>', size: 'county', bounds: [[14, -180], [72, -56]]}", 'JusticeMap_black' => "'http://www.justicemap.org/tile/{size}/black/{z}/{x}/{y}.png', { attribution: '<a href=\"http://www.justicemap.org/terms.php\">Justice Map</a>', size: 'county', bounds: [[14, -180], [72, -56]]}", 'JusticeMap_hispanic' => "'http://www.justicemap.org/tile/{size}/hispanic/{z}/{x}/{y}.png', {   attribution: '<a href=\"http://www.justicemap.org/terms.php\">Justice Map</a>', size: 'county', bounds: [[14, -180], [72, -56]]}", 'JusticeMap_multi' => "'http://www.justicemap.org/tile/{size}/multi/{z}/{x}/{y}.png', { attribution: '<a href=\"http://www.justicemap.org/terms.php\">Justice Map</a>', size: 'county', bounds: [[14, -180], [72, -56]]}", 'JusticeMap_nonWhite' => "'http://www.justicemap.org/tile/{size}/nonwhite/{z}/{x}/{y}.png', {   attribution: '<a href=\"http://www.justicemap.org/terms.php\">Justice Map</a>', size: 'county', bounds: [[14, -180], [72, -56]]}", 'JusticeMap_white' => "'http://www.justicemap.org/tile/{size}/white/{z}/{x}/{y}.png', { attribution: '<a href=\"http://www.justicemap.org/terms.php\">Justice Map</a>', size: 'county', bounds: [[14, -180], [72, -56]]}", 'JusticeMap_plurality' => "'http://www.justicemap.org/tile/{size}/plural/{z}/{x}/{y}.png', {    attribution: '<a href=\"http://www.justicemap.org/terms.php\">Justice Map</a>', size: 'county', bounds: [[14, -180], [72, -56]]}", 'WaymarkedTrails_hiking' => "'https://tile.waymarkedtrails.org/hiking/{z}/{x}/{y}.png', {   maxZoom: 18,    attribution: 'Map data: &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors | Map style: &copy; <a href=\"https://waymarkedtrails.org\">waymarkedtrails.org</a> (<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY-SA</a>)'}", 'WaymarkedTrails_cycling' => "'https://tile.waymarkedtrails.org/cycling/{z}/{x}/{y}.png', { maxZoom: 18,    attribution: 'Map data: &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors | Map style: &copy; <a href=\"https://waymarkedtrails.org\">waymarkedtrails.org</a> (<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY-SA</a>)'}", 'WaymarkedTrails_mtb' => "'https://tile.waymarkedtrails.org/mtb/{z}/{x}/{y}.png', { maxZoom: 18,    attribution: 'Map data: &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors | Map style: &copy; <a href=\"https://waymarkedtrails.org\">waymarkedtrails.org</a> (<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY-SA</a>)'}", 'WaymarkedTrails_slopes' => "'https://tile.waymarkedtrails.org/slopes/{z}/{x}/{y}.png', {   maxZoom: 18,    attribution: 'Map data: &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors | Map style: &copy; <a href=\"https://waymarkedtrails.org\">waymarkedtrails.org</a> (<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY-SA</a>)'}", 'WaymarkedTrails_riding' => "'https://tile.waymarkedtrails.org/riding/{z}/{x}/{y}.png', {   maxZoom: 18,    attribution: 'Map data: &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors | Map style: &copy; <a href=\"https://waymarkedtrails.org\">waymarkedtrails.org</a> (<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY-SA</a>)'}", 'WaymarkedTrails_skating' => "'https://tile.waymarkedtrails.org/skating/{z}/{x}/{y}.png', { maxZoom: 18,    attribution: 'Map data: &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors | Map style: &copy; <a href=\"https://waymarkedtrails.org\">waymarkedtrails.org</a> (<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY-SA</a>)'}", 'OpenAIP' => "'http://{s}.tile.maps.openaip.net/geowebcache/service/tms/1.0.0/openaip_basemap@EPSG%3A900913@png/{z}/{x}/{y}.{ext}', {   attribution: '<a href=\"https://www.openaip.net/\">openAIP Data</a> (<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY-NC-SA</a>)', ext: 'png', minZoom: 4, maxZoom: 14,    tms: true,  detectRetina: true, subdomains: '12'}", ];

                    if($maptilelayer != 0){
                        $maptilelayer = explode(",", $maptilelayer);        
                        if(!empty($maptilelayer and is_array($maptilelayer))){
                            foreach ($maptilelayer as $key => $value) {
                                ?>
                                var <?php echo $value; ?> = L.tileLayer(<?php echo $maptilelayers[$value] ;?>);
                                <?php echo $value;?>.addTo(mymap); <?php
                            }
                        }
                    } ?> <?php echo $markerjshtml; ?>

                    $(".adminz_marker_list_item").on("click",function(){
                        var index = $(this).data("marker");                        
                        <?php echo $clickitemhtml; ?>
                    });

                    $(".address_opt1").on("keyup, change",function(){
                        var current_opt1 = $(this).val();                
                        $(".address_opt2").find("option").each(function(){
                            $(this).removeClass("hidden");
                            if(current_opt1 == 'All'){
                                
                            }else{
                                if($(this).data('opt1') !== current_opt1){
                                    $(this).addClass("hidden");
                                }
                            }
                            
                        });
                        $(".address_opt2").find('option[data-opt1="'+current_opt1+'"]').each(function(){
                            $(".address_opt2").val($(this).val());
                            return false;
                        });
                    });
                    $(".address_opt2").on("keyup, change",function(){                
                        var current_opt2 = $(this).val();
                        var target_for_opt1 = "";
                        $(this).find("option").each(function(){                    
                            if($(this).text() == current_opt2){
                                target_for_opt1 = $(this).data('opt1');
                            }
                        });

                        if(target_for_opt1){
                            $(".address_opt1 option").each(function(){
                                if($(this).val() == target_for_opt1){
                                    $(".address_opt1").val($(this).val());
                                }
                            });
                        }
                    });

                    $(".address_opt_search").on("click",function(){
                        var opt1 = $(".address_opt1").val();
                        var opt2 = $(".address_opt2").val();
                        $(".adminz_marker_list>div").each(function(){
                            $(this).addClass('hidden');
                            if(opt1=='All'){
                                $(this).removeClass('hidden');
                            }else{
                                if($(this).data('address_opt_1') == opt1 & $(this).data('address_opt_2') == opt2) {
                                $(this).removeClass('hidden');
                            };
                            };                    
                        });
                    });
                    function markerOnClick(e){                        
                        mymap.flyTo(e.latlng,<?php echo $markerzoom; ?>);
                    };
                })(jQuery);
                
            });
            
        </script>

        <?php
    } ?>

    <?php

    $cssjs = ob_get_clean();
    add_action('wp_footer',function() use($cssjs){
        echo apply_filters('adminz_output_debug',$cssjs);
    });
    return apply_filters('adminz_output_debug',$mainhtml);
}
function adminz_map_item_shortcode( $atts, $content = null ) {
    if(isset( $_POST['ux_builder_action'] )){
        extract(shortcode_atts(array(
            'image'=>'',
            'marker'=>'',
            'title'=>'',
            'address'=>'',
            'phone_number'=>'',
            'description'=>'',
            'address_opt_1'=>'',
            'address_opt_2'=>'',
            'latlong'=>'',
            'popup'=>'',
            'flyto'=>'',
            'address_text'=>'',
            'phone_number_text'=> ''
        ), $atts));    
        ob_start();
        ?>
        <div style="border: 1px solid #8a8a8a29; padding: 10px; margin: 15px;">
            <div><?php echo $title; ?></div>
            <div><?php echo $address; ?></div>
            <div><?php echo $phone_number; ?></div>
            <div><?php echo $description; ?></div>
        </div>
        <?php
        return ob_get_clean();
    }
    return "<div>".json_encode($atts)."</div>";
}