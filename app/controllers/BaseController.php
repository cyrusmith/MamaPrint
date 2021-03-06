<?php

class BaseController extends Controller
{

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

    protected function withErrorMessage($response, $msg)
    {
        return $response->with('error', $msg);
    }

    protected function withSuccessMessage($response, $msg)
    {
        return $response->with('success', $msg);
    }

    protected function restify($data, $links = [])
    {
        $data['links'] = $links;
        return $data;
    }

}
