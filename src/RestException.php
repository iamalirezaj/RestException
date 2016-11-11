<?php

namespace Josh\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Anetwork\Respond\Facades\Respond;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait RestException
{
    /**
     * Datas of response
     *
     * @var array
     */
    protected $datas = [];

    /**
     * In development mode return trace of exception
     *
     * @var array
     */
    protected $trace = [];

    /**
     * Creates a new JSON response based on exception type.
     *
     * @author Alireza Josheghani <josheghani.dev@gmail.com>
     * @since  11 Nov 2016
     * @param  Exception $exception
     * @return \Anetwork\Respond\Facades\Respond
     */
    protected function getJsonResponseForException(Exception $exception)
    {
        $message = $exception->getMessage();

        $this->getTrace($exception);

        if ($exception instanceof ModelNotFoundException) {
            return $this->modelNotFound($message);
        } elseif ($exception instanceof NotFoundHttpException) {
            return $this->httpNotFound();
        } elseif ($exception instanceof BadRequestHttpException) {
            return $this->badRequest($message);
        }

        return $this->errorException($message);
    }

    /**
     * Returns json response for generic bad request.
     *
     * @author Alireza Josheghani <josheghani.dev@gmail.com>
     * @since  11 Nov 2016
     * @param  string $message
     * @return \Anetwork\Respond\Facades\Respond
     */
    protected function badRequest($message)
    {
        $this->datas['message'] = $message;

        return Respond::setStatusCode(400)
            ->setStatusText('bad request !')
            ->respondWithResult($this->datas);
    }

    /**
     * Returns json response ErrorException
     *
     * @author Alireza Josheghani <josheghani.dev@gmail.com>
     * @since  11 Nov 2016
     * @param  string $message
     * @return \Anetwork\Respond\Facades\Respond
     */
    protected function errorException($message)
    {
        $this->datas['message'] = $message;

        return Respond::setStatusCode(400)
            ->setStatusText('ErrorException !')
            ->respondWithMessage($this->datas);
    }

    /**
     * Returns json response for Eloquent model not found exception.
     *
     * @author Alireza Josheghani <josheghani.dev@gmail.com>
     * @since  11 Nov 2016
     * @param  string $message
     * @return \Anetwork\Respond\Facades\Respond
     */
    protected function modelNotFound($message)
    {
        $this->datas['message'] = $message;

        return Respond::setStatusCode(404)
            ->setStatusText('model notfound !')
            ->respondWithResult($this->datas);
    }

    /**
     * Returns json response for Eloquent model not found exception.
     *
     * @author Alireza Josheghani <josheghani.dev@gmail.com>
     * @since  11 Nov 2016
     * @return \Anetwork\Respond\Facades\Respond
     */
    protected function httpNotFound()
    {
        $this->datas['message'] = 'Sorry, the page you are looking for could not be found.';

        return Respond::setStatusCode(404)
            ->setStatusText('NotFoundHttpException')
            ->respondWithResult($this->datas);
    }

    /**
     * Determines if request is an api call.
     *
     * If the request URI contains '/api/v'.
     *
     * @author Alireza Josheghani <josheghani.dev@gmail.com>
     * @since  11 Nov 2016
     * @param  Request $request
     * @return bool
     */
    protected function isApiCall(Request $request)
    {
        if( strpos($request->getUri(), 'api') || $request->wantsJson()) {
            return true;
        }

        return false;
    }

    /**
     * Get Trace in development mode
     *
     * @author Alireza Josheghani <josheghani.dev@gmail.com>
     * @since  11 Nov 2016
     * @param  Exception $exception
     * @return void
     */
    private function getTrace(Exception $exception)
    {

        if (config('app.debug')) {

            $traces = $exception->getTrace();

            if (!empty($traces)) {
                foreach ($traces as $trace){
                    unset($trace['args']);
                    $this->trace[] = $trace;
                }

                $this->datas['trace'] = $this->trace;
            }
        }
    }

    /**
     * Render json exception
     *
     * @author Alireza Josheghani <josheghani.dev@gmail.com>
     * @since  11 Nov 2016
     * @param  Request   $request
     * @param  Exception $exception
     * @return \Anetwork\Respond\Facades\Respond
     */
    public function renderRestException(Request $request, Exception $exception)
    {

        if (!$this->isApiCall($request)) {
            return parent::render($request, $exception);
        }

        return $this->getJsonResponseForException($exception);
    }

}