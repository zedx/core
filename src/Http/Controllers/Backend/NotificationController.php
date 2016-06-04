<?php

namespace ZEDx\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $notifications = Notification::visible()->recents();
        $notifications = $this->filterNotificationsByDateRange($request, $notifications)->paginate(20);
        $currency = setting('currency');

        return view_backend('notification.index', compact('notifications', 'currency'));
    }

    /**
     * Mark all notifications as read.
     *
     * @param  Request $request
     *
     * @return Reponse
     */
    public function readall(Request $request)
    {
        if (\Request::ajax()) {
            return [
                'success' => (boolean) Notification::readall(),
            ];
        } else {
            abort(404);
        }
    }

    protected function filterNotificationsByDateRange(Request $request, $notifications)
    {
        try {
            if ($request->has('dateFrom') && $request->has('dateTo')) {
                $from = Carbon::createFromFormat('m/d/Y', $request->get('dateFrom'))->toDateTimeString();
                $to = Carbon::createFromFormat('m/d/Y', $request->get('dateTo'))->toDateTimeString();
                $notifications = $notifications->whereBetween('created_at', [$from, $to]);
            }
        } finally {
            return $notifications;
        }
    }
}
