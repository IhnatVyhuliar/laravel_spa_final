<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\NameValidationRequest;

use App\Services\GetCommentSWithRepliesService;
use App\Http\Requests\SubmitEmailRequest;
use App\Helpers\NumberHelper;


class ShowCommentController extends Controller
{
    /* 
    
        Normal sort with dates 
        
    */
    public function sortByDate(): object
    {
       return app(GetCommentSWithRepliesService::class)->getDefaultComments();        
    }

    public function sortByDateReversed(): object
    {   
        return app(GetCommentSWithRepliesService::class)->getDefaultCommentsReversed();  
    }


    public function offset(string $offset)
    {
        if (NumberHelper::checkIsNumber($offset)){
            $offset_in = NumberHelper::convertToNumber($offset);
            
            return app(GetCommentSWithRepliesService::class)->getDefaultComments($offset_in);
        }
        return app(GetCommentSWithRepliesService::class)->getDefaultComments();
    }

    public function offsetReversed(string $offset): object
    {
        if (NumberHelper::checkIsNumber($offset)){
            $offset_in = intval($offset);
            
            return app(GetCommentSWithRepliesService::class)->getDefaultCommentsReversed($offset_in);
        }
        return app(GetCommentSWithRepliesService::class)->getDefaultCommentsReversed();
    }

    /* 

        Sort by email
    
    */
    public function sortByEmail(SubmitEmailRequest $request): object
    {   
        return app(GetCommentSWithRepliesService::class)->sortByEmail($request->email);
    }

    
    public function sortByEmailOffset(SubmitEmailRequest $request, string $offset): object
    {   
        if (NumberHelper::checkIsNumber($offset))
        {
            $offset_in = NumberHelper::convertToNumber($offset);
            return app(GetCommentSWithRepliesService::class)->sortCommentsByEmail($request->email, $offset_in);
        }

        return app(GetCommentSWithRepliesService::class)->sortCommentsByEmail($request->email);
    }


    
    public function sortByEmailReversed(SubmitEmailRequest $request): object
    {  
        return app(GetCommentSWithRepliesService::class)->sortCommentsByEmailReversed($request->email);
    }

    public function sortByEmailReversedOffset(SubmitEmailRequest $request, string $offset):object
    {
        if (NumberHelper::checkIsNumber($offset))
        {
            $offset_in = NumberHelper::convertToNumber($offset);
            return $this->sortCommentsByEmailReversed($request->email, $offset_in);
        }

        return app(GetCommentSWithRepliesService::class)->sortCommentsByEmailReversed($request->email);
    }

    public function sortByName(NameValidationRequest $request): object
    {  
       return app(GetCommentSWithRepliesService::class)->sortCommentsByName(ucfirst(htmlspecialchars($request->name)));
    }

    public function sortByNameOffset(NameValidationRequest $request, string $offset): object
    {   
        if (NumberHelper::checkIsNumber($offset))
        {
            $offset_in = NumberHelper::convertToNumber($offset);
            
            return app(GetCommentSWithRepliesService::class)->sortCommentsByName(ucfirst(htmlspecialchars($request->name)), $offset_in);
        }

        return app(GetCommentSWithRepliesService::class)->sortCommentsByName($request->name);
    }

    
    public function sortByNameReversed(NameValidationRequest $request): object
    {  
        return app(GetCommentSWithRepliesService::class)->sortCommentsByNameReversed(ucfirst(htmlspecialchars($request->name)));
    }

    public function sortByNameReversedOffset(NameValidationRequest $request, string $offset):object
    {
        if (NumberHelper::checkIsNumber($offset))
        {
            $offset_in = NumberHelper::convertToNumber($offset);
            return app(GetCommentSWithRepliesService::class)->sortCommentsByName(ucfirst(htmlspecialchars($request->name), $offset_in));
        }

        return app(GetCommentSWithRepliesService::class)->sortCommentsByName(ucfirst(htmlspecialchars($request->name)));
    }
    
}
