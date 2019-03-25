<?php
App::uses('DispatcherFilter', 'Routing');

use DDTrace\Tag;
use DDTrace\Type;
use DDTrace\GlobalTracer;
use DDTrace\Util\TryCatchFinally;

class DatadogFilter extends DispatcherFilter
{
    public function beforeDispatch(CakeEvent $event)
    {
		if ($this->invokeRequestSpan($event)) {
			$this->injectViewSpan();
		}

        return true;
	}

	private function invokeRequestSpan(CakeEvent $event)
	{
        $scope = GlobalTracer::get()->getRootScope();
        if (null === $scope) {
            return false;
        }
        $appName = Configure::read('Datadog.appName');
        $request = $event->data['request'];
        $controller = $request->params['controller'];
		$action = $request->params['action'];

        $span = $scope->getSpan();
        $span->overwriteOperationName('cakephp.request');
        $span->setTag(Tag::SERVICE_NAME, $appName);
        $span->setTag(Tag::RESOURCE_NAME, $controller . '/' . $action);
        $span->setTag('cakephp.controller', $controller);
        $span->setTag('cakephp.action', $action);
        $span->setTag('http.url', Router::url($request->here(), true));

		return true;
	}

	private function injectViewSpan()
	{
        dd_trace('View', 'render', function () {
			$args = func_get_args();
            $scope = GlobalTracer::get()->startActiveSpan('cakephp.view');
            $span = $scope->getSpan();
            $span->setTag(Tag::SPAN_TYPE, Type::WEB_SERVLET);
            return TryCatchFinally::executePublicMethod($scope, $this, 'render', $args);
        });
	}
}
