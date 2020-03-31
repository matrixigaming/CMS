<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<li class="header">ACCOUNT NAVIGATION</li>
    <li>
      <a href="{{ url('/home') }}">
        <i class="fa fa-dashboard"></i> <span>Account - Dashboard</span>
      </a>
    </li>
    <li>
      <a href="index.php?page=admin-accounting">
        <i class="fa fa-dollar"></i> <span>Accounting</span>
      </a>
    </li>
    <li>
      <a href="{{ url('/user/list') }}">
        <i class="fa fa-users"></i> <span>Manage Users</span> <small class="label pull-right bg-green">new</small>
      </a>
    </li>
    <li>
      <a href="{{ url('/role_permission') }}">
        <i class="fa fa-users"></i> <span>Create Role & Permission</span> 
      </a>
    </li>
    <li>
      <a href="{{ url('/role_permission/manage') }}">
        <i class="fa fa-users"></i> <span>Manage Role & Permission</span>
      </a>
    </li>
    <li>
      <a href="{{ url('/departments') }}">
        <i class="fa fa-gear"></i> <span>Manage Department</span>
      </a>
    </li>
    <li>
      <a href="{{ url('/companies') }}">
        <i class="fa fa-gear"></i> <span>Manage Company</span>
      </a>
    </li>
    <li>
      <a href="{{ url('/messages') }}">
        <i class="fa fa-bell"></i> <span>Notifications</span>
      </a>
    </li>                     
    <li>
      <a href="{{ url('/pages') }}">
        <i class="fa fa-file"></i> <span>Pages</span>
      </a>
    </li>
    <li>
      <a href="{{ url('/contact/requests') }}">
        <i class="fa fa-envelope"></i> <span>Requests</span>
      </a>
    </li>
    <li>
      <a href="#">
        <i class="fa fa-dollar"></i> <span>Help and Support</span>
      </a>
    </li>    
    <li>
      <a href="#">
        <i class="fa fa-bell"></i> <span>User Tools</span>
      </a>
    </li> 
