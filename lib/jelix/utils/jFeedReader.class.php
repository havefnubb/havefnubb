<?php
/**
* @package     jelix
* @subpackage  utils
* @author      Yannick Le Guédart
* @contributor 
* @copyright   2008 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

require(LIB_PATH.'clearbricks/net/class.net.socket.php');
require(LIB_PATH.'clearbricks/net.http/class.net.http.php');
require(LIB_PATH.'clearbricks/net.http.feed/class.feed.parser.php');
require(LIB_PATH.'clearbricks/net.http.feed/class.feed.reader.php');

/**
 * To send http request
 * @package    jelix
 * @subpackage utils
 * @see netHttp
 */
class jFeedReader extends feedReader 
{
}
