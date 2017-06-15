#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import requests
import sys


__version__     = '0.5'
__copyright__   = 'GNU General Public License v3.0'
__maintainer__  = 'Tas-sos'
__author__      = 'Tas-sos'
__email__       = 'tas-sos@g-lts.info'


def main():

    try:
        response = requests.get("http://localhost:8000/wp-json/wp/v2/donationboxes/168")
    except requests.exceptions.RequestException as e:
        print(e)
        sys.exit(1)

    data = response.json()

    project_title           = data['title']
    project_content         = data['content']['rendered']
    project_current_amount  = data['project_current_amount']
    project_target_amount   = data['project_target_amount']
    project_status          = data['project_status']
    project_stylesheet_file = data['project_stylesheet_file']
    project_organizations   = data['project_organizations']   # One or more..

    print(project_organizations)


if __name__ == '__main__':
    main()
