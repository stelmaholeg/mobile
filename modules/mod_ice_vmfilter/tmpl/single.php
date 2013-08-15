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
        $tmpOutput = array();
        $displayFields[$type->parameter_name]= array();
        foreach ($fields as  $field ):

            $output = modIceVmFilterHelper::getFieldsInfor( $field, $type, true );
          
            $tmplURL = $output['url']; 
            if( !preg_match('/'.$tmplURL.'/', $currentURL) ){
             //   $currentURL = "&product_type_id=". $type->product_type_id.$tmplURL;
                $fields['_'.$field]['addURLAdded'] =    $tmplURL."&product_type_id=". $type->product_type_id;
               //  echo   $fields[$field]['addURLAdded'];die;
            }

            if( isset($fields['_'.$field]['addURLAdded']) ){
                $p = $fields['_'.$field]['addURLAdded'] .$output['params'];
            } else {
                $p =$output['params'];
            }

            $isExisted = in_array(  substr($p, 1), $urlparams);
            if( !$isExisted ){
                $url =$currentURL.$p;
            }else {
                $url = str_replace( $p, "", $currentURL);
               
                if( count(explode("&product_type_".$type->product_type_id."_".$type->parameter_name, $url)) <= 1 ){
                    $url = str_replace( $tmplURL, "", $url );
                    $url = str_replace("&product_type_id=". $type->product_type_id, "",$url);   
                }
            }
            if( $isExisted ){
               $tmpOutput[]=$type->product_type_id.":".$field;
            }
            
     ?>
        <?php if ( $output['count'] ) : ?>
         <?php
              ++$idex;
                 $btnSeeMore='';
                if( ($idex-1) == $maxParamsDisplay ) {
                     $btnSeeMore  = '<li class="ice-vmfilter-gp" id="ice-vmfilter-gp-'.$type->parameter_name.'"><span>'.JText::_('See more...').'</span></li>';
                     $class='class="ice-vmfilter-gp-'.$type->parameter_name.' ice-vmfilter-hide '. ($isExisted ? ' ice-vmfilter-selected':'').'"';
                } elseif( $idex > $maxParamsDisplay ) {  // die;
                    $class='class="ice-vmfilter-gp-'.$type->parameter_name.' ice-vmfilter-hide '. ($isExisted ? ' ice-vmfilter-selected':'').'"';
                }else {
                    $class= $isExisted ? 'class="ice-vmfilter-selected"':'';
                }


                if( $isExisted ){
                    $displayFields[$type->parameter_name]=array();
                    $displayFields[$type->parameter_name][$field]=array(
                            'url'=>$url,
                            'count'=>$output['count'],
                            'label'=> $field . ' ' . $type->parameter_unit,
                            'class'=>str_replace("ice-vmfilter-hide", '',$class),
                            'isExisted'=>true,
                            'btnSeeMore'=>''
                    ); 
                    break;
                }else {
                    $displayFields[$type->parameter_name][$field]=array(
                            'url'=>$url,
                            'count'=>$output['count'],
                            'label'=> $field . ' ' . $type->parameter_unit,
                            'class'=>$class,
                            'isExisted'=>false,
                             'btnSeeMore'=> $btnSeeMore
                    );

                }
           ?>
        <?php  endif; ?>
    <?php endforeach; ?>
        <?php if(!empty($displayFields[$type->parameter_name]) ) :   ?>
        <?php foreach( $displayFields[$type->parameter_name] as $f => $_out  ):?>
            <?php echo $_out['btnSeeMore'];?>
            <li <?php echo $_out['class'];?>>
               <a href="<?php echo  $_out['url']; ?>">
                <span><?php echo  $_out['label'] ?> </span>
                <?php if( $_out['isExisted'] )  : ?>
                <span class="ice-vmfilter-remove"><img title="<?php echo JText::_("Close");?>" src="<?php echo JURI::base();?>modules/mod_ice_vmfilter/assets/remove.png" ><span>
                <?php endif; ?>
               </a>
                 <?php if( $isCountProducts ) : ?>
               (<?php echo $_out['count']; ?>)
               <?php endif; ?>
            </li>
        <?php endforeach; ?>
        <?php endif;  // die; ?>
    </ul>
</div>
<?php endforeach; //  die; ?>
