<?php namespace App\Core\Controllers\Admin;

use App;
use App\Core\Models\Role;
use App\Core\Models\Review;
use App\Core\Models\User;
use App\Haku\Searchers\Users as UsersSearcher;
use Auth;
use Confide;
use Config;
use Input;
use Lomake;
use Mail;
use Redirect;
use Session;
use View;

class Reviews extends Base
{
    use \CRUD;
    protected $viewPath = 'admin.reviews';

    protected $crudOptions = [
        'modelClass'  => 'App\Core\Models\Review',
        'layout'      => 'layouts.admin',
        'langPrefix'  => 'as.review',
        'showTab'	  => false,
        'actionsView' => 'admin.reviews.actions',
        'bulkActions' => ['approve', 'reject'],
        'presenters'  => [
            'status' 	 => ['App\Core\Controllers\Admin\Reviews', 'presentStatus'],
            'avg_rating' => ['App\Core\Controllers\Admin\Reviews', 'presentAvg'],
        ]
    ];



    //--------------------------------------------------------------------------
    // PRESENTERS
    //--------------------------------------------------------------------------
    public static function presentStatus($value, $item)
    {
        if ($item->status !== null) {

        	$statusClasses = [
        		Review::STATUS_INIT 	=> 'label label-default',
        		Review::STATUS_APPROVED => 'label label-success',
        		Review::STATUS_REJECTED => 'label label-danger',
        	];

	    	$class = !empty($statusClasses[$item->status]) ? $statusClasses[$item->status] : '';
	    	return '<span class="' . $class . '">'.trans(sprintf('as.review.%s', $item->status)).'</span>';

    	}

        return $value;
    }

    public static function presentAvg($value, $item)
    {
    	return number_format($item->avg_rating, 2);
    }


	/**
	* 
	* {@inheritdoc}
	* 
	*/
    public function getModel()
    {
        if ($this->model === null) {
            $this->model = App::make($this->getModelClass());
            $this->crudFillable = $this->model->fillable;
        }

        return $this->model;
    }

    /**
     * Approve a list of reviews
     *
     * @param array $ids
     *
     * @return void
     */
    protected function approve($ids)
    {
        Review::whereIn('id', $ids)->update(['status' => Review::STATUS_APPROVED]);
    }

    /**
     * Reject a list of reviews
     *
     * @param array $ids
     *
     * @return void
     */
    protected function reject($ids)
    {
        Review::whereIn('id', $ids)->update(['status' => Review::STATUS_REJECTED]);
    }
}