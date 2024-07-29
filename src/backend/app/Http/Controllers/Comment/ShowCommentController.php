<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\NameValidationRequest;

use App\Services\GetCommentSWithRepliesService;
use App\Http\Requests\SubmitEmailRequest;
use App\Helpers\NumberHelper;


class ShowCommentController extends Controller
{
 
    public function sortCommentsByDate(): object
    {
       return app(GetCommentSWithRepliesService::class)->getDefaultComments();        
    }

    public function sortCommentsByDateReversed(): object
    {   
        return app(GetCommentSWithRepliesService::class)->getDefaultCommentsReversed();  
    }

    public function sortCommentsOffset(string $offset)
    {
        if (NumberHelper::checkIsNumber($offset)){
            $offset_in = NumberHelper::convertToNumber($offset);
            return app(GetCommentSWithRepliesService::class)->getDefaultComments($offset_in);
        }
        return app(GetCommentSWithRepliesService::class)->getDefaultComments();
    }

    public function sortCommentsReversedOffset(string $offset): object
    {
        if (NumberHelper::checkIsNumber($offset)){
            $offset_in = intval($offset);
            return app(GetCommentSWithRepliesService::class)->getDefaultCommentsReversed($offset_in);
        }
        return app(GetCommentSWithRepliesService::class)->getDefaultCommentsReversed();
    }

    public function sortCommentsByEmail(SubmitEmailRequest $request): object
    {   
        return app(GetCommentSWithRepliesService::class)->sortCommentsByEmail($request->email);
    }

    
    public function sortCommentsByEmailOffset(SubmitEmailRequest $request, string $offset): object
    {   
        if (NumberHelper::checkIsNumber($offset))
        {
            $offset_in = NumberHelper::convertToNumber($offset);
            return app(GetCommentSWithRepliesService::class)->sortCommentsByEmail($request->email, $offset_in);
        }

        return app(GetCommentSWithRepliesService::class)->sortCommentsByEmail($request->email);
    }
    
    public function sortCommentsByEmailReversed(SubmitEmailRequest $request): object
    {  

        return app(GetCommentSWithRepliesService::class)->sortCommentsByEmailReversed($request->email);
    }

    public function sortCommentsByEmailReversedOffset(SubmitEmailRequest $request, string $offset):object
    {
        if (NumberHelper::checkIsNumber($offset))
        {
            $offset_in = NumberHelper::convertToNumber($offset);
            return app(GetCommentSWithRepliesService::class)->sortCommentsByEmailReversed($request->email, $offset_in);
        }
        return app(GetCommentSWithRepliesService::class)->sortCommentsByEmailReversed($request->email);
    }

    public function sortCommentsByName(NameValidationRequest $request): object
    {  
       return app(GetCommentSWithRepliesService::class)->sortCommentsByName(ucfirst(htmlspecialchars($request->name)));
    }

    public function sortCommentsByNameOffset(NameValidationRequest $request, string $offset): object
    {   
        if (NumberHelper::checkIsNumber($offset))
        {
            $offset_in = NumberHelper::convertToNumber($offset);
            return app(GetCommentSWithRepliesService::class)->sortCommentsByName(ucfirst(htmlspecialchars($request->name)), $offset_in);
        }
        return app(GetCommentSWithRepliesService::class)->sortCommentsByName($request->name);
    }

    
    public function sortCommentsByNameReversed(NameValidationRequest $request): object
    {  
        return app(GetCommentSWithRepliesService::class)->sortCommentsByNameReversed(ucfirst(htmlspecialchars($request->name)));
    }

    public function sortCommentsByNameReversedOffset(NameValidationRequest $request, string $offset):object
    {
        if (NumberHelper::checkIsNumber($offset))
        {
            $offset_in = NumberHelper::convertToNumber($offset);
            return app(GetCommentSWithRepliesService::class)->sortCommentsByName(ucfirst(htmlspecialchars($request->name)), $offset_in);
        }
        return app(GetCommentSWithRepliesService::class)->sortCommentsByName(ucfirst(htmlspecialchars($request->name)));
    }
    
}
