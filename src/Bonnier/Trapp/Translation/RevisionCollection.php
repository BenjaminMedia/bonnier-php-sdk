<?php
namespace Bonnier\Trapp\Translation;

use Pecee\Http\HttpResponse;
use Bonnier\IndexSearch\IServiceCollection;
use Pecee\Http\Rest\RestCollection;

class RevisionCollection extends RestCollection implements IServiceCollection {

    protected $total;
    protected $skip;
    protected $limit;

    public function setResponse(HttpResponse $response, $formattedResponse) {
        //$this->skip = $formattedResponse['skip'];
        //$this->limit = $formattedResponse['limit'];
        $this->total = $formattedResponse['total'];
    }

    public function translationId($id) {
        $this->service->getHttpRequest()->addPostData('translation_id', $id);
        return $this;
    }

    /**
     * @param $data
     * @depricated Warning this method is depricated, use $this->getRequest->addPostData() instead.
     */
    public function setData($data) {
        $this->service->getHttpRequest()->setPostData($data);
    }

    /**
     * @return int
     */
    public function getTotal() {
        return $this->total;
    }


}