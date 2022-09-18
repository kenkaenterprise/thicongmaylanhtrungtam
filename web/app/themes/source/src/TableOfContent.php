<?php

namespace Phangia\App;

/**
 * Class TableOfContent
 * @author Hoang Phan <hoang.phan@kenkadigit.com>
 */
class TableOfContent
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $index;

    /**
     * @return string
     */
    public function get_index()
    {
        if (null === $this->content) {
            throw new \Exception('Content has not been initialized');
        }

        return <<<INDEX
 {$this->index}
INDEX;


    }

    /**
     * @var array
     */
    protected $options = [
        'heading_levels' => [
            1,
            2,
            3,
            4,
            5,
            6
        ]
    ];

    /**
     * @var array
     */
    protected $collision_collector;

    /**
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @param $find
     * @param $replace
     * @param $content
     * @return false|string
     */
    protected function extract_headings(&$find, &$replace, $content = '')
    {
        $matches = [];
        $anchor = '';
        $items = false;

        // reset the internal collision collection as the_content may have been triggered elsewhere
        // eg by themes or other plugins that need to read in content such as metadata fields in
        // the head html tag, or to provide descriptions to twitter/facebook
        $this->collision_collector = [];

        if (is_array($find) && is_array($replace) && $content) {
            // get all headings
            // the html spec a
            //llows for a maximum of 6 heading depths
            if (preg_match_all('/(<h([1-6]{1})[^>]*>).*<\/h\2>/msuU', $content, $matches, PREG_SET_ORDER)) {

                // remove undesired headings (if any) as defined by heading_levels
                if (count($this->options['heading_levels']) != 6) {
                    $new_matches = [];
                    for ($i = 0; $i < count($matches); $i++) {
                        if (in_array($matches[$i][2], $this->options['heading_levels'])) {
                            $new_matches[] = $matches[$i];
                        }
                    }
                    $matches = $new_matches;
                }


                // remove empty headings
                $new_matches = [];
                for ($i = 0; $i < count($matches); $i++) {
                    if (trim(strip_tags($matches[$i][0])) != false) {
                        $new_matches[] = $matches[$i];
                    }
                }
                if (count($matches) !== count($new_matches)) {
                    $matches = $new_matches;
                }


                for ($i = 0; $i < count($matches); $i++) {
                    // get anchor and add to find and replace arrays
                    $anchor = $this->url_anchor_target($matches[$i][0]);
                    $find[] = $matches[$i][0];
                    $replace[] = str_replace(
                        [
                            $matches[$i][1], // start of heading
                            '</h' . $matches[$i][2] . '>', // end of heading
                        ],
                        [
                            $matches[$i][1] . '<span id="' . $anchor . '">',
                            '</span></h' . $matches[$i][2] . '>',
                        ],
                        $matches[$i][0]
                    );

                    $items .= '<li><a href="#' . $anchor . '">';
                    $items .= count($replace) . ' ';
                    $items .= strip_tags($matches[$i][0]) . '</a></li>';
                }

                $items = $this->build_hierarchy($matches);
            }
        }

        return $items;
    }

    protected function build_hierarchy(&$matches)
    {
        $current_depth = 100;  // headings can't be larger than h6 but 100 as a default to be sure
        $html = '';
        $numbered_items = [];
        $numbered_items_min = null;

        // reset the internal collision collection
        $this->collision_collector = [];

        // find the minimum heading to establish our baseline
        for ($i = 0; $i < count($matches); $i++) {
            if ($current_depth > $matches[$i][2]) {
                $current_depth = (int)$matches[$i][2];
            }
        }

        $numbered_items[$current_depth] = 0;
        $numbered_items_min = $current_depth;

        for ($i = 0; $i < count($matches); $i++) {

            if ($current_depth === (int)$matches[$i][2]) {
                $html .= '<li>';
            }

            // start lists
            if ($current_depth !== (int)$matches[$i][2]) {
                for ($current_depth; $current_depth < (int)$matches[$i][2]; $current_depth++) {
                    $numbered_items[$current_depth + 1] = 0;
                    $html .= '<ul><li>';
                }
            }

            // list item
            if (in_array($matches[$i][2], $this->options['heading_levels'])) {
                $html .= '<a href="#' . $this->url_anchor_target($matches[$i][0]) . '">';
                // attach leading numbers when lower in hierarchy
                $html .= '<span class="toc_number toc_depth_' . ($current_depth - $numbered_items_min + 1) . '">';
                for ($j = $numbered_items_min; $j < $current_depth; $j++) {
                    $number = ($numbered_items[$j]) ? $numbered_items[$j] : 0;
                    $html .= $number . '.';
                }

                $html .= ($numbered_items[$current_depth] + 1) . '</span> ';
                $numbered_items[$current_depth]++;
                $html .= strip_tags($matches[$i][0]) . '</a>';
            }

            // end lists
            if (count($matches) - 1 !== $i) {
                if ($current_depth > (int)$matches[$i + 1][2]) {
                    for ($current_depth; $current_depth > (int)$matches[$i + 1][2]; $current_depth--) {
                        $html .= '</li></ul>';
                        $numbered_items[$current_depth] = 0;
                    }
                }

                if ((int)@$matches[$i + 1][2] === $current_depth) {
                    $html .= '</li>';
                }
            } else {
                // this is the last item, make sure we close off all tags
                for ($current_depth; $current_depth >= $numbered_items_min; $current_depth--) {
                    $html .= '</li>';
                    if ($current_depth !== $numbered_items_min) {
                        $html .= '</ul>';
                    }
                }
            }
        }

        return $html;
    }

    /**
     * @param $content
     * @return array|mixed|string|string[]
     */
    public function get_content()
    {
        $content = $this->content;
        $items = '';
        $css_classes = '';
        $anchor = '';
        $find = [];
        $replace = [];
        $custom_toc_position = strpos($content, '<!--TOC-->');

        $items = $this->extract_headings($find, $replace, $content);

        if ($items) {


            $css_classes = trim($css_classes);

            // an empty class="" is invalid markup!
            if (!$css_classes) {
                $css_classes = ' ';
            }

            // add container, toc title and list items
            $html = '<div id="toc_container" class="' . $css_classes . '">';
            $html .= '<div id="ez-toc-title-container"><p class="ez-toc-title">Nội dung</p>
                            <span class="ez-toc-title-toggle"><a
                                        class="ez-toc-pull-right ez-toc-btn ez-toc-btn-xs ez-toc-btn-default ez-toc-toggle"
                                        style="display: inline;"><i class="ez-toc-glyphicon ez-toc-icon-toggle"></i></a></span>
                        </div>';

            $html .= '<ul class="toc_list">' . $items . '</ul></div>' . "\n";

            $this->index = $html;


            $content = $this->mb_find_replace($find, $replace, $content);
            $this->content = $content;
        }

        return $this->content;
    }

    protected function mb_find_replace(&$find = false, &$replace = false, &$string = '')
    {
        if (is_array($find) && is_array($replace) && $string) {
            // check if multibyte strings are supported
            if (function_exists('mb_strpos')) {
                for ($i = 0; $i < count($find); $i++) {
                    $string =
                        mb_substr($string, 0, mb_strpos($string, $find[$i])) . // everything before $find
                        $replace[$i] . // its replacement
                        mb_substr($string, mb_strpos($string, $find[$i]) + mb_strlen($find[$i])); // everything after $find
                }
            } else {
                for ($i = 0; $i < count($find); $i++) {
                    $string = substr_replace(
                        $string,
                        $replace[$i],
                        strpos($string, $find[$i]),
                        strlen($find[$i])
                    );
                }
            }
        }

        return $string;
    }

    /**
     * Returns a clean url to be used as the destination anchor target
     */
    protected function url_anchor_target($title)
    {
        $return = false;

        if ($title) {
            $return = trim(strip_tags($title));

            // convert accented characters to ASCII
            $return = remove_accents($return);

            // replace newlines with spaces (eg when headings are split over multiple lines)
            $return = str_replace(["\r", "\n", "\n\r", "\r\n"], ' ', $return);

            // remove &amp;
            $return = str_replace('&amp;', '', $return);

            // remove non alphanumeric chars
            $return = preg_replace('/[^a-zA-Z0-9 \-_]*/', '', $return);

            // convert spaces to _
            $return = str_replace(
                ['  ', ' '],
                '_',
                $return
            );

            // remove trailing - and _
            $return = rtrim($return, '-_');

            // lowercase everything?
            $return = strtolower($return);

            // if blank, then prepend with the fragment prefix
            // blank anchors normally appear on sites that don't use the latin charset
            if (!$return) {
                $return = '_';
            }


            $return = str_replace('_', '-', $return);
            $return = str_replace('--', '-', $return);

        }

        if (array_key_exists($return, $this->collision_collector)) {
            $this->collision_collector[$return]++;
            $return .= '-' . $this->collision_collector[$return];
        } else {
            $this->collision_collector[$return] = 1;
        }

        return apply_filters('toc_url_anchor_target', $return);
    }
}