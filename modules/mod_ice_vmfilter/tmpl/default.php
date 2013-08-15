<?php if( $params->get('show_text_above','1') ): ?>
<p class="ice-vmfilter-textabove">
<?php echo $textAbove; ?>
</p>
<?php endif; ?>
<?php foreach( $types as $type ) : ?>
<div class="ice-vmfilter-type">
    <h4><?php echo $type->parameter_label; ?></h4>
     <ul>
    <?php
        $fields = explode( ";", $type->parameter_values );
        $idex=0;
        $tmpfield = '';

        $urlAdded = $currentURL;
        foreach ($fields as  $field ):
            $tmplURL = "&product_type_".$type->product_type_id."_".$type->parameter_name."_comp=find_in_set_any";
            if( !preg_match('/'.$tmplURL.'/', $currentURL) ){
             //   $currentURL = "&product_type_id=". $type->product_type_id.$tmplURL;
                $fields['_'.$field]['addURLAdded'] =    $tmplURL."&product_type_id=". $type->product_type_id;
               //  echo   $fields[$field]['addURLAdded'];die;
            }

            if( isset($fields['_'.$field]['addURLAdded']) ){
                $p = $fields['_'.$field]['addURLAdded'] ."&product_type_".$type->product_type_id."_".$type->parameter_name."[]=".$field;
            } else {
                $p ="&product_type_".$type->product_type_id."_".$type->parameter_name."[]=".$field;
            }            $output = modIceVmFilterHelper::getFieldsInfor( $field, $type, true );
            $isExisted = in_array(  substr($p, 1), $urlparams);
            if( !$isExisted ){
                $url =$currentURL.$p;
            }else {
                $url = str_replace( $p, "", $currentURL);
                if( count(explode("&product_type_".$type->product_type_id."_".$type->parameter_name."[]", $url)) <= 1 ){
                    $url = str_replace( $tmplURL, "", $url );
                    $url = str_replace("&product_type_id=". $type->product_type_id, "",$url);
                    
                }


            }
     ?>
        <?php if ( $output['count'] ) : ?>
         <?php
              ++$idex;
                if( ($idex-1) == $maxParamsDisplay ) {
                    echo '<li class="ice-vmfilter-gp" id="ice-vmfilter-gp-'.$type->parameter_name.'"><span>'.$params->get('text_whenhide','See more...').'</span></li>';
                     $class='class="ice-vmfilter-gp-'.$type->parameter_name.' ice-vmfilter-hide '. ($isExisted ? ' ice-vmfilter-selected':'').'"';
                } elseif( $idex > $maxParamsDisplay ) {  // die;
                    $class='class="ice-vmfilter-gp-'.$type->parameter_name.' ice-vmfilter-hide'. ($isExisted ? ' ice-vmfilter-selected':'').'"';
                }else {
                    $class= $isExisted ? 'class="ice-vmfilter-selected"':'';
                }
           
           ?>
         <li <?php echo $class;?>>
            <a href="<?php echo  $url; ?>">
                <span><?php echo $field . ' ' . $type->parameter_unit; ?> </span>
                <?php if( $isExisted ) : ?>
                	<span class="ice-vmfilter-remove"><img title="<?php echo JText::_("Close");?>" src="<?php echo JURI::base();?>modules/mod_ice_vmfilter/assets/remove.png" ><span>
                <?php endif?>
                </a>
               <?php if( $isCountProducts ) : ?>
               (<?php echo $output['count']; ?>)
               <?php endif; ?>
               
               
        </li>
        <?php  endif; ?>
    <?php endforeach; ?>
    </ul>
</div>
<?php endforeach; //  die; ?>
