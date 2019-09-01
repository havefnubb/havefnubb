<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* jSmtp, based on SMTP, a  PHP SMTP class by Chris Ryan
*
* Define an SMTP class that can be used to connect
* and communicate with any SMTP server. It implements
* all the SMTP functions defined in RFC821 except TURN.
*
* @package     jelix
* @subpackage  utils
* @author      Laurent Jouanneau
* @copyright   2006-2008 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require(LIB_PATH.'phpMailer/class.smtp.php');
class jSmtp extends SMTP{
}
