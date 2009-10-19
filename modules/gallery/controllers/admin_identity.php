<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2009 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */
class Admin_Identity_Controller extends Admin_Controller {
  public function index() {
    $view = new Admin_View("admin.html");
    $view->content = new View("admin_identity.html");
    $view->content->available = Identity::active();
    $view->content->active = module::get_var("gallery", "identity_provider", "user");
    print $view;
  }

  public function confirm() {
    access::verify_csrf();

    $v = new View("admin_identity_confirm.html");
    $v->new_provider = $this->input->post("provider");

    print $v;
  }

  public function change() {
    access::verify_csrf();

    $active_provider = module::get_var("gallery", "identity_provider", "user");
    $providers = Identity::active();

    $new_provider = $this->input->post("provider");

    if ($new_provider != $active_provider) {
      module::event("identity_change", $new_provider);

      // @todo this type of collation is questionable from an i18n perspective
      message::success(t("Changed to %description",
                         array("description" => $providers->$new_provider)));
    }

    url::redirect("admin/identity");
  }
}

