<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace MagmaCore\Twig\Extensions;

class IconNavExtension
{

    /** @var string */
    protected const TRASH_KEY = 'trash';
    /** @var string */
    protected const EDIT_MODAL = 'edit_model';
    /** @var string */
    protected const TRASH_CLASS = 'uk-text-danger';
    /** @var string */
    protected const DEFAULT_ICON_RATIO = 1;

    /**
     * Generate the necessary HTML which generates an unorder list of icons which can
     * be used as navigation. By default the unorder list of render horizontally. But
     * can be set vertical by setting the $vertical argument to true on the method. The list
     * takes in multiple arguments in order to try and populate the necessary attributes
     * whilst still allowing for complete overriding
     *
     * @param array $icons
     * @param array|Object $row
     * @param string $controller
     * @param boolean $vertical
     * @return string
     */
    public function iconNav(array $icons = [], array $row = null, string $controller = null, bool $vertical = false) : string
    {
        $html = '';
        if (is_array($icons) && count($icons) > 0) {
            $html .= '<ul class="' . ($vertical === true ? 'uk-iconnav uk-iconnav-vertical' : 'uk-iconnav') . '">';
                foreach ($icons as $key => $_icon) {
                    extract ($_icon);

                    $toggle = (isset($toggle) && $toggle == true);
                    $toggleID = (isset($toogle_id) ? $toggle_id : '');
                    $toggleTarget = (isset($toggle_target) ? $toggle_target : '');

                    if ($row !=null) {
                        $getPath = $this->determinePath($_icon, $key, $row, $controller);
                        if ($key === self::TRASH_KEY || $key === self::EDIT_MODAL) {
                            $toggle = true;
                        }
                        if ($key === self::TRASH_KEY) {
                            $class = self::TRASH_CLASS;
                        }
                    } else {
                        $getPath = $this->determinePath($_icon, $key, null, $controller);
                    }

                    $html .= "\n" . sprintf(
                        '<li><a href="%s" uk-icon="%s%s"%s%s%s></a>',

                        ($getPath ? $getPath : $toggleID),
                        /** Set icon defaults to array key */
                        (isset($icon) ? "icon:{$icon}" : "icon:{$key}"),
                        /** Set icon ratio ie (size) defaults to 1 */
                        (isset($ratio) ? ";ratio:{$ratio}" : ";ratio:" . self::DEFAULT_ICON_RATIO),
                        /** Set an hover tooltip. Defaults to array key */
                        (isset($tooltip) ? "uk-tooltip=\"{$tooltip}\"" : 'uk-tooltip="' . str_replace((array('_', '-')), ' ', ucwords($key)) . '"'),

                        ($toggle ? ' uk-toggle' . ($toggleTagrget != '' ? '="target:' . $toggleTagrget . '; animation: uk-animation-slide-bottom-small uk-animation-fade; queued: true"' : '') . '' : ''),

                        (isset($class) ? " class=\"{$class}\"" : "")
                    );

                    /** Provide an option for using Closure callbacks within the iconNav array */
                    if ($row !=null) {
                        if (isset($callback) && $callback !=null && $callback instanceof \Closure) {
                            if(is_callable($callback)) {
                                $html .= call_user_func_array($callback, $row);
                            }
                        }
                    }
                    /** Trigger modal is using  */
                    if (isset($modal) && $modal === true && $toggleID !='') {
                        $html .= $this->getModal($_icon);
                    }
                    /** Trigger popout if using */
                    if (isset($popout) && $popout === true && $toggleID !='') {
                        $html .= $this->getModal($_icon);
                    }
                    /** Trigger container is using */
                    if (isset($container) && $container === true) {
                        $html .= $this->getContainer($_icon);
                    }

                    $html .= '</li>' . "\n";    
                }
            $html .= "</ul>";

            return $html;
        }
    }

    /**
     * Method which determine what to do based on a specific key phrase. ie if 
     * array key contains the string 'trash'. Then we can automatically generate the
     * necessary modal or path to perform the relevant action. There's is also an option
     * to specify your own path your using the path key within your array and setting your 
     * path value.
     *
     * @param array $icons
     * @param string $key
     * @param mixed $row
     * @param string $controller
     * @return string
     */
    public function determinePath(array $icons, string $key, $row = null, string $controller) : string
    {
        extract ($icons);
        if (isset($key) && $key !='') {
            switch ($key) :
                case 'trash' :
                    $target = "#delete-modal-{$controller}-{$row['id']}";
                    break;
                case 'edit_modal' :
                    if (isset($toggleModalEdit) && $toggleModalEdit == true) {
                        $target = "#edit-modal-{$controller}-{$row['id']}";
                    } else {
                        $target = "/admin/{$controller}/{$row['id']}/edit";
                    }
                    break;
                case 'edit' :
                case 'file-edit' :
                        $target = "/admin/{$controller}/{$row['id']}/edit";
                    break;
                default :
                    $target = (isset($path) ? $path : '');
                    break;
            endswitch;

            return $target;
        }
        return false;
    }

    /**
     * Get the modal model
     *
     * @param array $iconValue
     * @return string
     */
    public function getModal(array $iconValue) : string
    {
        $html = '';
        if (!empty($iconValue)) {
            $html .= '<div id="' . (isset($iconValue['toggle_id']) ? str_replace('#', '', $iconValue['toggle_id']) : '') . '"' . (isset($iconValue['modal_size']) ? ' class="' . $iconValue['modal_size'] . '"' : ' uk-modal') . '>';

                $html .= '<div class="uk-modal-dialog uk-modal-body">';
                    $html .= '<button class="uk-modal-close-default" type="button" uk-close></button>';
                    $html .= '<h2 class="uk-modal-title">' . (isset($iconValue['modal_title']) ? $iconValue['modal_title'] : '') . '</h2>';

                    $html .= (isset($iconValue['modal_content']) ? $iconValue['modal_content'] : '');

                $html .= '</div>';

            $html .= '</div>' . "\n";

            return $html;
         }
    }

    /**
     * Get the container wrapper
     *
     * @param array $iconValue
     * @return string
     */
    public function getContainer(array $iconValue) : string
    {
        $html = '';
        if (!empty($iconValue)) {
            $html .= '<div class="uk-navbar-dropdown" uk-drop="mode: click; cls-drop: uk-navbar-dropdown; boundary: !nav">';

                $html .= '<div class="uk-grid-small uk-flex-middle" uk-grid>';
                    $html .= '<div class="uk-width-expand">';
                        $html .= (isset($iconValue['content']) ? $iconValue['content'] : '');
                    $html .= '</div>';

                    $html .= '<div class="uk-width-auto">';
                        $html .= '<a class="uk-navbar-dropdown-close" href="#" uk-close></a>';
                    $html .= '</div>';
                $html .= '</div>';

            $html .= '</div>' . "\n";

            return $html;
         }

    }

    /**
     * Undocumented function
     *
     * @param integer $rowID
     * @param string $controller
     * @param string $title
     * @param string $desc
     * @return string
     */
    public function confirmationModal(int $rowID, string $controller, string $title, string $desc) : string
    {
        $html = '';
        if (!empty($rowID)) {
            $html .= "<div id=\"delete-modal-{$controller}-{$rowID}\" uk-modal style=\"z-index:1000\">\n";

                $html .= '<div class="uk-modal-dialog uk-modal-body">';
                    $html .= '<h2 class="uk-modal-title">' . (isset($title) ? $title : '') . '</h2>';
                    $html .= "<form method=\"POST\" action=\"/admin/{$controller}/{$rowID}/delete}\" id=\"confirm-delete-modal-form-{$controller}-{$rowID}\">";

                        $html .= "<p>{$desc}</p>";
                        $html .= "<p>";
                            $html .= '<button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>';
                            $html .= '<button class="uk-button uk-button-danger" name="' . $controller . '-delete" type="submit">Delete</button>';
                        $html .= "</p>";

                    $html .= '</form>';
                    $html .= (isset($iconValue['modal_content']) ? $iconValue['modal_content'] : '');

                $html .= '</div>';

            $html .= '</div>' . "\n";

            return $html;
         }

    }

}