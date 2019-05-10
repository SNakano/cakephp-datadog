<?php
App::uses('DispatcherFilter', 'Routing');

class DatadogFilter extends DispatcherFilter
{
    public function beforeDispatch(CakeEvent $event)
    {
        if (!function_exists('dd_trace')) {
            return true;
        }

        if ($this->invokeRequestSpan($event)) {
            $this->injectViewSpan();
        }

        return true;
    }

    private function invokeRequestSpan(CakeEvent $event)
    {
        $scope = DDTrace\GlobalTracer::get()->getRootScope();
        if (null === $scope) {
            return false;
        }
        $serviceName = Configure::read('Datadog.serviceName');
        $request = $event->data['request'];
        $controller = $request->params['controller'];
        $action = $request->params['action'];

        $span = $scope->getSpan();
        $span->overwriteOperationName('cakephp.request');
        $span->setTag(DDTrace\Tag::SERVICE_NAME, $serviceName);
        $span->setTag(DDTrace\Tag::RESOURCE_NAME, $controller . '/' . $action);
        $span->setTag('cakephp.controller', $controller);
        $span->setTag('cakephp.action', $action);
        $span->setTag('http.url', Router::url($request->here(), true));

        return true;
    }

    private function injectViewSpan()
    {
        dd_trace('View', 'render', function () {
            $args = func_get_args();
            $scope = DDTrace\GlobalTracer::get()->startActiveSpan('cakephp.view');
            $span = $scope->getSpan();
            $span->setTag(DDTrace\Tag::SPAN_TYPE, DDTrace\Type::WEB_SERVLET);
            return DDTrace\Util\TryCatchFinally::executePublicMethod($scope, $this, 'render', $args);
        });
    }
}
