<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <zhenqiang.zhang@hotmail.com>
 * @copyright (c) KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
namespace Kant\Http\Formatter;

use Kant\Kant;
use Kant\Foundation\Component;
use Kant\Helper\Json;

/**
 * JsonResponseFormatter formats the given data into a JSON or JSONP response content.
 *
 * It is used by [[Response]] to format response data.
 *
 * To configure properties like [[encodeOptions]] or [[prettyPrint]], you can configure the `response`
 * application component like the following:
 *
 * ```php
 * 'response' => [
 * // ...
 * 'formatters' => [
 * \Kant\Http\Response::FORMAT_JSON => [
 * 'class' => 'Kant\Http\JsonResponseFormatter',
 * 'prettyPrint' => KANT_DEBUG, // use "pretty" output in debug mode
 * // ...
 * ],
 * ],
 * ],
 * ```
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class JsonResponseFormatter extends Component implements ResponseFormatterInterface
{

    /**
     *
     * @var boolean whether to use JSONP response format. When this is true, the [[Response::data|response data]]
     *      must be an array consisting of `data` and `callback` members. The latter should be a JavaScript
     *      function name while the former will be passed to this function as a parameter.
     */
    public $useJsonp = false;

    /**
     *
     * @var integer the encoding options passed to [[Json::encode()]]. For more details please refer to
     *      <http://www.php.net/manual/en/function.json-encode.php>.
     *      Default is `JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE`.
     *      This property has no effect, when [[useJsonp]] is `true`.
     * @since 2.0.7
     */
    public $encodeOptions = 320;

    /**
     *
     * @var bool whether to format the output in a readable "pretty" format. This can be useful for debugging purpose.
     *      If this is true, `JSON_PRETTY_PRINT` will be added to [[encodeOptions]].
     *      Defaults to `false`.
     *      This property has no effect, when [[useJsonp]] is `true`.
     * @since 2.0.7
     */
    public $prettyPrint = false;

    /**
     * Formats the specified response.
     * 
     * @param Response $response
     *            the response to be formatted.
     */
    public function format($response)
    {
        if ($this->useJsonp) {
            $this->formatJsonp($response);
        } else {
            $this->formatJson($response);
        }
    }

    /**
     * Formats response data in JSON format.
     * 
     * @param Response $response            
     */
    protected function formatJson($response)
    {
        $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
        if ($response->data !== null) {
            $options = $this->encodeOptions;
            if ($this->prettyPrint) {
                $options |= JSON_PRETTY_PRINT;
            }
            $response->content = Json::encode($response->data, $options);
        }
    }

    /**
     * Formats response data in JSONP format.
     * 
     * @param Response $response            
     */
    protected function formatJsonp($response)
    {
        $response->headers->set('Content-Type', 'application/javascript; charset=UTF-8');
        if (is_array($response->data) && isset($response->data['data'], $response->data['callback'])) {
            $response->content = sprintf('%s(%s);', $response->data['callback'], Json::htmlEncode($response->data['data']));
        } elseif ($response->data !== null) {
            $response->content = '';
            Kant::warning("The 'jsonp' response requires that the data be an array consisting of both 'data' and 'callback' elements.", __METHOD__);
        }
    }
}
