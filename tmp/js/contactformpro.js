JHTML::_('behavior.formvalidation');

        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration('
            function cfp_send(f) {
               var form = $(f);

               form.getElement(\'input[type=submit]\').disabled=true;

               if (document.formvalidator.isValid(f)) {
                    ContactFormBox.sendMessage(f);
               }
               else {
                  var msg = \'\';

                  if(form.getElementById(\'sender_name\').hasClass(\'invalid\')){
                    msg = msg + \'<p>'.JText::_('PLG_SYSTEM_CONTACTFORMPRO_SENDER_NAME_MISSING').'</p>\';
                  }
                  if(form.getElementById(\'sender_email\').hasClass(\'invalid\')){
                    msg = msg + \'<p>'.JText::_('PLG_SYSTEM_CONTACTFORMPRO_SENDER_EMAIL_MISSING').'</p>\';
                  }
                  if(form.getElementById(\'subject\').hasClass(\'invalid\')){
                    msg = msg + \'<p>'.JText::_('PLG_SYSTEM_CONTACTFORMPRO_SUBJECT_MISSING').'</p>\';
                  }
                  if(form.getElementById(\'message\').hasClass(\'invalid\')){
                    msg = msg + \'<p>'.JText::_('PLG_SYSTEM_CONTACTFORMPRO_MESSAGE_MISSING').'</p>\';
                  }

                  $(\'cfpResponseDiv\').set(\'html\', msg);
                  ContactFormBox.changeMedia(\'cfpResponseDiv\', {width:400,height:200});
               }

               form.getElement(\'input[type=submit]\').disabled = false;

               return false;
            }'
        );