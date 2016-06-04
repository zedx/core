<?php

namespace ZEDx\Http\Controllers\Backend;

use Auth;
use ZEDx\Http\Requests\FirewallRequest;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Events\Ip\IpWillBeBlacklisted;
use ZEDx\Events\Ip\IpWillBeWhitelisted;
use ZEDx\Events\Ip\IpWillBeRemovedFromBlacklist;
use ZEDx\Events\Ip\IpWillBeRemovedFromWhitelist;
use Firewall;

class FirewallController extends Controller
{
    /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
      $blacklist = $this->readFile(head(config('firewall.blacklist')));
      $whitelist = $this->readFile(head(config('firewall.whitelist')));

      return view_backend('firewall.index', compact('blacklist', 'whitelist'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(FirewallRequest $request)
  {
      $ip = $request->get('ip');
      if (Firewall::ipIsValid($ip)) {
          $this->addIp($request->get('type'), $ip);
      }

      return redirect()->route('zxadmin.firewall.index');
  }

  /**
   * Add Ip To Config.
   *
   * @param string $type
   * @param string $ip
   */
  protected function addIp($type, $ip)
  {
      $this->notify('add', $type, $ip);
      $file = head(config("firewall.{$type}"));
      $list = $this->readFile($file);
      if (! in_array($ip, $list)) {
          file_put_contents($file, $ip, FILE_APPEND | LOCK_EX);
      }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   *
   * @return Response
   */
  public function destroy(FirewallRequest $request, $ip)
  {
      $ip = base64_decode($ip);
      if (Firewall::ipIsValid($ip)) {
          $this->deleteIp($request->get('type'), $ip);
      }

      return redirect()->route('zxadmin.firewall.index');
  }

  /**
   * Delete Ip From Config.
   *
   * @param string $type
   * @param string $ip
   */
  protected function deleteIp($type, $ip)
  {
      $this->notify('delete', $type, $ip);
      $file = head(config("firewall.{$type}"));
      $list = $this->readFile($file);
      if (($key = array_search($ip, $list)) !== false) {
          unset($list[$key]);
      }
      file_put_contents($file, implode(PHP_EOL, $list));
  }

    protected function readFile($file)
    {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        return $lines;
    }

    protected function notify($action, $type, $ip)
    {
        $admin = Auth::guard('admin')->user();
        if ($action == 'add') {
            if ($type == 'blacklist') {
                event(new IpWillBeBlacklisted($admin->name, $admin->id, 'admin', $ip));
            } else {
                event(new IpWillBeWhitelisted($admin->name, $admin->id, 'admin', $ip));
            }
        } else {
            if ($type == 'blacklist') {
                event(new IpWillBeRemovedFromBlacklist($admin->name, $admin->id, 'admin', $ip));
            } else {
                event(new IpWillBeRemovedFromWhitelist($admin->name, $admin->id, 'admin', $ip));
            }
        }
    }
}
