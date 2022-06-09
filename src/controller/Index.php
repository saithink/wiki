<?php
namespace saithink\wiki\controller;

use ReflectionClass;
use think\App;
use think\facade\View;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use saithink\wiki\annotations\WikiItem;
use saithink\wiki\annotations\WikiMenu;
use saithink\wiki\annotations\WikiRequest;
use saithink\wiki\annotations\WikiResponse;
use Symfony\Component\ClassLoader\ClassMapGenerator;

class Index
{

    /** @var \think\App */
    protected $app;

    /** @var Reader */
    protected $reader;

    /** @var Array */
    protected $wikiList;

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        // 控制器初始化
        $this->initialize();
    }

    public function initialize()
    {
        $this->reader = new AnnotationReader();
        $this->wikiList = [];
    }

    public function index()
    {
        $app = config('wiki.scan_app') ?? 'index';
        $app = request()->get('app') ?? $app;
        AnnotationRegistry::registerLoader('class_exists');
        $dir = $this->app->getRootPath() . 'app' . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR;
        if (!is_dir($dir)) {
            return '请检查应用['.$app.']是否存在';
        }

        $this->scanDir($dir);
        $result = [];
        foreach ($this->wikiList as $key => $info) {
            $result[$info['group']][] = $info;
        }
        $viewPath = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR;
        View::config(['view_path' => $viewPath]);
        View::assign('public', config('wiki.public'));
        View::assign('data', $result);
        View::assign('app', config('wiki.app_name') . ' ' . config('wiki.app_ver'));
        return View::fetch('index/index');
    }

    protected function scanDir($dir)
    {
        foreach (ClassMapGenerator::createMap($dir) as $class => $path) {
            $refClass = new ReflectionClass($class);
            // 使用WikeMenu注解路由的控制器类
            if ($resource = $this->reader->getClassAnnotation($refClass, WikiMenu::class)) {
                $this->parse($refClass);
            }
        }
    }

    protected function parse($reflectionClass)
    {
        // 读取类的信息
        $test = $this->reader->getClassAnnotation($reflectionClass, WikiMenu::class);

        // 读取反射类的所有方法
        $methods = $reflectionClass->getMethods();
        $api = [];

        foreach ($methods as $method) {
            // 读取所有注解
            $all = $this->reader->getMethodAnnotations($method);

            if (count($all) === 0) {
                continue;
            }

            $request = [];
            $response = [];
            $temp = [];

            foreach ($all as $item) {
                if ($item instanceof WikiItem) {
                    $temp['title'] = $item->name;
                    $temp['rule'] = $item->route;
                    $temp['method'] = $item->method;
                    $temp['description'] = $item->description;
                }
                if ($item instanceof WikiRequest) {
                    array_push($request, (array) $item);
                }
                if ($item instanceof WikiResponse) {
                    array_push($response, (array) $item);
                }
            }
            $temp['mock']['request'] = $request;
            $temp['mock']['response'] = $response;

            $api[$method->name] = $temp;

        }
        $temp = ['title' => $test->name, 'group' => $test->group, 'description' => $test->description, 'api' => $api];
        array_push($this->wikiList, ['group' => $test->group, 'data' => $temp]);
    }
}
