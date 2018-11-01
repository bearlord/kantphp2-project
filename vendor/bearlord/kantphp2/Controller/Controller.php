<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <zhenqiang.zhang@hotmail.com>
 * @copyright (c) KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
namespace Kant\Controller;

use Kant\Kant;
use Kant\Foundation\Component;
use Kant\View\View;
use Kant\Action\ActionEvent;
use Kant\Action\InlineAction;
use Kant\Exception\InvalidArgumentException;
use Kant\Exception\BadRequestHttpException;

/**
 * Base Controller
 *
 * @property \Kant\View\View $view The view application component that is used to render various view files. This property is read-only.
 *          
 */
class Controller extends Component
{

    /**
     * @event ActionEvent an event raised right before executing a controller action.
     * You may set [[ActionEvent::isValid]] to be false to cancel the action execution.
     */
    const EVENT_BEFORE_ACTION = 'beforeActions';

    /**
     * @event ActionEvent an event raised right after executing a controller action.
     */
    const EVENT_AFTER_ACTION = 'afterActions';

    /**
     * Route Pattern explicit
     */
    const ROUTE_PATTERN_EXPLICIT = 'explicit';

    /**
     * Route Pattern implicit
     */
    const ROUTE_PATTERN_IMPLICIT = 'implicit';

    /**
     *
     * @var string the ID of this controller.
     */
    public $id;

    /**
     * @var Module the module that this controller belongs to.
     */
    public $module;

    /**
     *
     * @var string the ID of the action that is used when the action ID is not specified
     *      in the request. Defaults to 'index'.
     */
    public $defaultAction = 'index';

    public $actionSuffix = 'Action';

    /**
     *
     * @var type
     */
    public $view;

    /**
     * @var null|string|false the name of the layout to be applied to this controller's views.
     * This property mainly affects the behavior of [[render()]].
     * Defaults to null, meaning the actual layout value should inherit that from [[module]]'s layout value.
     * If false, no layout will be applied.
     */
    public $layout = 'main';

    /**
     *
     * @var \Kant\Action\Action the action that is currently being executed. This property will be set
     *      by [[run()]] when it is called by [[Application]] to run an action.
     */
    public $action;

    /**
     * @param string $id the ID of this controller.
     * @param Module $module the module that this controller belongs to.
     * @param array $config name-value pairs that will be used to initialize the object properties.
     */
    public function __construct($id = "", $module = "", $config = [])
    {
        $this->id = $id;
        $this->module = $module;
        parent::__construct($config);
    }

    public function actions()
    {
        return [];
    }

    /**
     * initialize
     */
    public function init()
    {
        $this->view = Kant::$app->getView();
        $this->view->layout = $this->layout;
    }

    /**
     * Runs a request specified in terms of a route.
     */
    public function run()
    {

    }

    /**
     * Runs an action within this controller with the specified action ID and parameters.
     * If the action ID is empty, the method will use [[defaultAction]].
     * 
     * @param string $id
     *            the ID of the action to be executed.
     * @return mixed the result of the action.
     * @throws InvalidRouteException if the requested action ID cannot be resolved into an action successfully.
     * @see createAction()
     */
    public function runActions($id, $params = [])
    {
        $action = $this->createActions($id);
        if ($action === null) {
            throw new InvalidArgumentException('Unable to resolve the request: ' . $this->getUniqueId() . '/' . $id);
        }
        
        $oldAction = $this->action;
        $this->action = $action;
        
        $result = null;
        if ($this->beforeActions($action)) {
            // run the action
            $result = $action->runWithParams($params);
            $result = $this->afterActions($action, $result);
        }
        
        $this->action = $oldAction;
        
        return $result;
    }

    /**
     * Creates an action based on the given action ID.
     * The method first checks if the action ID has been declared in [[actions()]]. If so,
     * it will use the configuration declared there to create the action object.
     * If not, it will look for a controller method whose name is in the format of `actionXyz`
     * where `Xyz` stands for the action ID. If found, an [[InlineAction]] representing that
     * method will be created and returned.
     * 
     * @param string $id
     *            the action ID.
     * @return Action the newly created action instance. Null if the ID doesn't resolve into any action.
     */
    public function createActions($id)
    {
        if ($id === '') {
            $id = $this->defaultAction;
        }
        
        $actionMap = $this->actions();
        
        if (isset($actionMap[$id])) {
            return Kant::createObject($actionMap[$id], [
                $id,
                $this
            ]);
        } elseif (preg_match('/^[\w+\\-]+$/', $id) && strpos($id, '--') === false && trim($id, '-') === $id) {
            $methodName = $this->formatMethodName($id);
            if (method_exists($this, $methodName)) {
                $method = new \ReflectionMethod($this, $methodName);
                if ($method->isPublic() && $method->getName() === $methodName) {
                    return new InlineAction($id, $this, $methodName);
                }
            }
            return Kant::$container->call([
                $this,
                $methodName
            ]);
        }
        
        return null;
    }

    /**
     * Retrun the formatted method name
     *
     * @param string $id            
     * @return type
     */
    protected function formatMethodName($id)
    {
        if (strpos($id, $this->actionSuffix) > 1) {
            return $id;
        }
        return lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $id)))) . $this->actionSuffix;
    }

    /**
     * This method is invoked right before an action is executed.
     *
     * The method will trigger the [[EVENT_BEFORE_ACTION]] event. The return value of the method
     * will determine whether the action should continue to run.
     *
     * In case the action should not run, the request should be handled inside of the `beforeAction` code
     * by either providing the necessary output or redirecting the request. Otherwise the response will be empty.
     *
     * If you override this method, your code should look like the following:
     *
     * ```php
     * public function beforeActions($action)
     * {
     * // your custom code here, if you want the code to run before action filters,
     * // which are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl
     *
     * if (!parent::beforeAction($action)) {
     * return false;
     * }
     *
     * // other custom code here
     *
     * return true; // or false to not run the action
     * }
     * ```
     *
     * @param Action $action
     *            the action to be executed.
     * @return boolean whether the action should continue to run.
     */
    public function beforeActions($action)
    {
        $event = new ActionEvent($action);
        $this->trigger(self::EVENT_BEFORE_ACTION, $event);
        // return $event->isValid;
        if ($event->isValid) {
            if ($this->enableCsrfValidation && ! Kant::$app->getRequest()->validateCsrfToken()) {
                throw new BadRequestHttpException(Kant::t('kant', 'Unable to verify your data submission.'));
            }
            return true;
        }
    }

    /**
     * This method is invoked right after an action is executed.
     *
     * The method will trigger the [[EVENT_AFTER_ACTION]] event. The return value of the method
     * will be used as the action return value.
     *
     * If you override this method, your code should look like the following:
     *
     * ```php
     * public function afterActions($action, $result)
     * {
     * $result = parent::afterAction($action, $result);
     * // your custom code here
     * return $result;
     * }
     * ```
     *
     * @param Action $action
     *            the action just executed.
     * @param mixed $result
     *            the action return result.
     * @return mixed the processed action result.
     */
    public function afterActions($action, $result)
    {
        $event = new ActionEvent($action);
        $event->result = $result;
        $this->trigger(self::EVENT_AFTER_ACTION, $event);
        return $event->result;
    }

    /**
     * Returns the unique ID of the controller.
     * 
     * @return string the controller ID that is prefixed with the module ID (if any).
     */
    public function getUniqueId()
    {
        return $this->id;
    }

    /**
     * Redirects the browser to the specified URL.
     *
     * @param string|array $url
     *            the URL to be redirected to. This can be in one of the following formats:
     *            
     *            - a string representing a URL (e.g. "http://example.com")
     *            - a string representing a URL alias (e.g. "@example.com")
     *            - an array in the format of `[$route, ...name-value pairs...]` (e.g. `['site/index', 'ref' => 1]`)
     *            [[Url::to()]] will be used to convert the array into a URL.
     *            
     *            Any relative URL will be converted into an absolute one by prepending it with the host info
     *            of the current request.
     *            
     * @return Object Kant\Http\RedirectResponse
     */
    public function redirect($url)
    {
        return Kant::$app->redirect->to($url)
            ->withCookie(Kant::$app->response->headers->getCookies())
            ->send();
    }

    public function setIdOptions($options)
    {
        foreach ([
            'id',
            'module',
            'routePattern'
        ] as $value) {
            if (! empty($options[$value])) {
                $this->$value = $options[$value];
            }
        }
    }
	
	/**
	 * Renders a view and applies layout if available.
	 *
	 * The view to be rendered can be specified in one of the following formats:
	 *
	 * - [path alias](guide:concept-aliases) (e.g. "@app/views/site/index");
	 * - absolute path within application (e.g. "//site/index"): the view name starts with double slashes.
	 *   The actual view file will be looked for under the [[Application::viewPath|view path]] of the application.
	 * - absolute path within module (e.g. "/site/index"): the view name starts with a single slash.
	 *   The actual view file will be looked for under the [[Module::viewPath|view path]] of [[module]].
	 * - relative path (e.g. "index"): the actual view file will be looked for under [[viewPath]].
	 *
	 * To determine which layout should be applied, the following two steps are conducted:
	 *
	 * 1. In the first step, it determines the layout name and the context module:
	 *
	 * - If [[layout]] is specified as a string, use it as the layout name and [[module]] as the context module;
	 * - If [[layout]] is null, search through all ancestor modules of this controller and find the first
	 *   module whose [[Module::layout|layout]] is not null. The layout and the corresponding module
	 *   are used as the layout name and the context module, respectively. If such a module is not found
	 *   or the corresponding layout is not a string, it will return false, meaning no applicable layout.
	 *
	 * 2. In the second step, it determines the actual layout file according to the previously found layout name
	 *    and context module. The layout name can be:
	 *
	 * - a [path alias](guide:concept-aliases) (e.g. "@app/views/layouts/main");
	 * - an absolute path (e.g. "/main"): the layout name starts with a slash. The actual layout file will be
	 *   looked for under the [[Application::layoutPath|layout path]] of the application;
	 * - a relative path (e.g. "main"): the actual layout file will be looked for under the
	 *   [[Module::layoutPath|layout path]] of the context module.
	 *
	 * If the layout name does not contain a file extension, it will use the default one `.php`.
	 *
	 * @param string $view the view name.
	 * @param array $params the parameters (name-value pairs) that should be made available in the view.
	 * These parameters will not be available in the layout.
	 * @return string the rendering result.
	 * @throws InvalidParamException if the view file or the layout file does not exist.
	 */
	public function render($view, $params = [])
	{
		$content = $this->getView()->render($view, $params, $this);
		return $this->renderContent($content);
	}

	/**
	 * Renders a static string by applying a layout.
	 * @param string $content the static string being rendered
	 * @return string the rendering result of the layout with the given static string as the `$content` variable.
	 * If the layout is disabled, the string will be returned back.
	 * @since 2.0.1
	 */
	public function renderContent($content)
	{
		$layoutFile = $this->findLayoutFile($this->getView());
		if ($layoutFile !== false) {
			return $this->getView()->renderFile($layoutFile, ['content' => $content], $this);
		}
		return $content;
	}

	/**
	 * Renders a view without applying layout.
	 * This method differs from [[render()]] in that it does not apply any layout.
	 * @param string $view the view name. Please refer to [[render()]] on how to specify a view name.
	 * @param array $params the parameters (name-value pairs) that should be made available in the view.
	 * @return string the rendering result.
	 * @throws InvalidParamException if the view file does not exist.
	 */
	public function renderPartial($view, $params = [])
	{
		return $this->getView()->render($view, $params, $this);
	}

	/**
	 * Renders a view file.
	 * @param string $file the view file to be rendered. This can be either a file path or a [path alias](guide:concept-aliases).
	 * @param array $params the parameters (name-value pairs) that should be made available in the view.
	 * @return string the rendering result.
	 * @throws InvalidParamException if the view file does not exist.
	 */
	public function renderFile($file, $params = [])
	{
		return $this->getView()->renderFile($file, $params, $this);
	}

	/**
	 * Returns the view object that can be used to render views or view files.
	 * The [[render()]], [[renderPartial()]] and [[renderFile()]] methods will use
	 * this view object to implement the actual view rendering.
	 * If not set, it will default to the "view" application component.
	 * @return View|\Kant\Web\View the view object that can be used to render views or view files.
	 */
	public function getView()
	{
		if ($this->view === null) {
			$this->view = Kant::$app->getView();
		}
		return $this->view;
	}

	/**
	 * Sets the view object to be used by this controller.
	 * @param View|\yii\web\View $view the view object that can be used to render views or view files.
	 */
	public function setView($view)
	{
		$this->view = $view;
	}
	
	/**
     * Returns the directory containing view files for this controller.
     * The default implementation returns the directory named as controller [[id]] under the [[module]]'s
     * [[viewPath]] directory.
     * @return string the directory containing the view files for this controller.
     */
    public function getViewPath()
    {
        return $this->_viewPath;
    }

    /**
     * Sets the directory that contains the view files.
     * @param string $path the root directory of view files.
     * @throws InvalidParamException if the directory is invalid
     * @since 2.0.7
     */
    public function setViewPath($path)
    {
        $this->_viewPath = Kant::getAlias($path);
    }
	
	/**
     * Finds the applicable layout file.
     * @param View $view the view object to render the layout file.
     * @return string|bool the layout file path, or false if layout is not needed.
     * Please refer to [[render()]] on how to specify this parameter.
     * @throws InvalidParamException if an invalid path alias is used to specify the layout.
     */
    public function findLayoutFile($view)
    {
        $module = $this->module;

        if (is_string($this->layout)) {
            $layout = $this->layout;
        } elseif ($this->layout === null) {
            while ($module !== null && $module->layout === null) {
                $module = $module->module;
            }
            if ($module !== null && is_string($module->layout)) {
                $layout = $module->layout;
            }
        }

        if (!isset($layout)) {
            return false;
        }

        if (strncmp($layout, '@', 1) === 0) {
            $file = Kant::getAlias($layout);
        } elseif (strncmp($layout, '/', 1) === 0) {
            $file = Kant::$app->getLayoutPath() . DIRECTORY_SEPARATOR . substr($layout, 1);
        } else {
            $file = $module->getLayoutPath() . DIRECTORY_SEPARATOR . $layout;
        }

        if (pathinfo($file, PATHINFO_EXTENSION) !== '') {
            return $file;
        }
        $path = $file . '.' . $view->defaultExtension;
        if ($view->defaultExtension !== 'php' && !is_file($path)) {
            $path = $file . '.php';
        }

        return $path;
    }
}
