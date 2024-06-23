<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Http\Requests\SubmitEmailRequest;
use App\Rules\StringValidationRule;
use App\Helpers\NumberHelper;
class ShowCommentController extends Controller
{
    /* 
    
        Normal sort with dates 
        
    */
    public function sortByDate(): object
    {
        
       return app(CommentService::class)->getDefaultComments(false);        
    }

    public function sortByDateReverse(): object
    {   
        return app(CommentService::class)->getDefaultComments(true);  
    }

    /* 

        Sort by email
    
    */
    public function sortByEmail(SubmitEmailRequest $request): object
    {   
        return $this->sortByEmailWithParams($request->email);
    }

    
    public function offsetSortByEmail(SubmitEmailRequest $request, string $offset): object
    {   
        if (NumberHelper::checkIsNumber($offset))
        {
            $offset_in = NumberHelper::convertToNumber($offset);
            return app(CommentService::class)->getDefaultComments(false, $offset_in);
        }

        return $this->sortByEmailWithParams($request->email);
    }


    
    public function sortByEmailReverse(SubmitEmailRequest $request): object
    {  
        return $this->sortByEmailWithParams($request->email, 0, true);
    }

    public function sortByEmailReverseOffset(SubmitEmailRequest $request, string $offset):object
    {
        if (NumberHelper::checkIsNumber($offset))
        {
            $offset_in = NumberHelper::convertToNumber($offset);
            return $this->sortByEmailWithParams($request->email, $offset_in, true);
        }

        return $this->sortByEmailWithParams($request->email, 0, true);
    }

    private function sortByEmailWithParams(string $email, int $offset = 0, bool $reverse = false): object
    {   
        return app(CommentService::class)->sortByEmail($email, $offset, $reverse);
    }

    public function sortByName(Request $request): object
    {   
        $request->validate([
            'name' => ['required', 'string', new StringValidationRule],
        ]);
       return app(CommentService::class)->sortByName($request->name);
    }

    
    public function offset(string $offset): object
    {
        // return 'index';
        if (NumberHelper::checkIsNumber($offset)){
            $offset_in = NumberHelper::convertToNumber($offset);
            
            return app(CommentService::class)->getDefaultComments(false, $offset_in);
        }

        
    }

    public function offsetReverse(string $offset): object
    {
        if (!empty($offset)&& is_numeric($offset)&& $offset>=0){
            $offset_in = intval($offset);
            
            return app(CommentService::class)->getDefaultComments(true, $offset_in);
        }
        return app(CommentService::class)->getDefaultComments(true);
    }



    
}
