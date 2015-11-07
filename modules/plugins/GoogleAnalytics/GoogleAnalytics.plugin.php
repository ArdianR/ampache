<?php
/* vim:set softtabstop=4 shiftwidth=4 expandtab: */
/**
 *
 * LICENSE: GNU General Public License, version 2 (GPLv2)
 * Copyright 2001 - 2015 Ampache.org
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License v2
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */

class AmpacheGoogleAnalytics
{
    public $name           = 'GoogleAnalytics';
    public $categories     = 'stats';
    public $description    = 'Google Analytics statistics';
    public $url            = '';
    public $version        = '000001';
    public $min_ampache    = '370034';
    public $max_ampache    = '999999';

    // These are internal settings used by this class, run this->load to
    // fill them out
    private $tracking_id;

    /**
     * Constructor
     * This function does nothing...
     */
    public function __construct()
    {
        return true;
    }

    /**
     * install
     * This is a required plugin function. It inserts our preferences
     * into Ampache
     */
    public function install()
    {
        // Check and see if it's already installed
        if (Preference::exists('googleanalytics_tracking_id')) {
            return false;
        }

        Preference::insert('googleanalytics_tracking_id','Google Analytics Tracking ID','',100,'string','plugins');

        return true;
    }

    /**
     * uninstall
     * This is a required plugin function. It removes our preferences from
     * the database returning it to its original form
     */
    public function uninstall()
    {
        Preference::delete('googleanalytics_tracking_id');

        return true;
    }

    /**
     * upgrade
     * This is a recommended plugin function
     */
    public function upgrade()
    {
        return true;
    }

    /**
     * display_user_field
     * This display the module in user page
     */
    public function display_on_footer()
    {
        echo "<!-- Google Analytics -->\n";
        echo "<script>\n";
        echo "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){\n";
        echo "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\n";
        echo "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\n";
        echo "})(window,document,'script','//www.google-analytics.com/analytics.js','ga');\n";
        echo "ga('create', '" . scrub_out($this->tracking_id) . "', 'auto');\n";
        echo "ga('send', 'pageview');\n";
        echo "</script>\n";
    }

    /**
     * load
     * This loads up the data we need into this object, this stuff comes
     * from the preferences.
     */
    public function load($user)
    {
        $this->user = $user;
        $user->set_preferences();
        $data = $user->prefs;

        $this->tracking_id = trim($data['googleanalytics_tracking_id']);
        if (!strlen($this->tracking_id)) {
            debug_event($this->name,'No Tracking ID, user field plugin skipped','3');
            return false;
        }

        return true;
    }
}
