#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import requests
import datetime
from pprint import  pprint


__version__     = '1'
__copyright__   = 'GNU General Public License v3.0'
__maintainer__  = 'Tas-sos'
__author__      = 'Tas-sos'
__email__       = 'tas-sos@g-lts.info'


class DonationBox:
    """
    Κατηγορία η οποία είναι υπεύθυνη για τον συγχρονισμό των δεδομένων, μεταξύ του κεντρικού κόμβου και του donation box.

    Η παρούσα κατηγορία είναι υπεύθυνη ώστε να κρατάει ενημερωμένο το donation box από τον κεντρικό κόμβο,
    αλλά και να ενημερώνει τον κεντρικό κόμβο για αλλαγές ( προσθήκη νέας δωρεάς σε κάποιο project ) που γίνονται
    στο donation box.
    Η ορθή λειτουργία αυτού του αντικειμένου δηλαδή είναι μείζονος σημασίας.
    """

    def __init__(self):
        # Θα κρατάει (ή ίσος σε αρχείο) την τελευταία φορά που έκανε ενημέρωση από τον κεντρικό κόμβο.
        self.last_server_update = "2017-06-15"


    def update_from_server(self):
        """
        Μέθοδος η οποία έχει σκοπό να ελέγχει για τυχόν ενημερώσεις από την κεντρική ιστοσελίδα.

        Ελέγχει αν υπάρχουν νέα ή τυχόν ενημερωμένα έργα δωρεάς στην κεντρική ιστοσελίδα,
        αν υπάρχουν τότε κρατάει το id τους.

        Προσοχή! : Δεν ενημερώνει τα τοπικά δεδομένα. Το μόνο που κάνει είναι να ενημερώνει το σύστημα πως υπάρχουν
        έργα δωρεάς προς ενημέρωση και είναι αυτά εδώ.

        :return: Τίποτα. Κατ' ουσία όμως μεταβιβάσει σε μια άλλη μέθοδο τα Ids των έργων δωρεάς που θα πρέπει να ενημερωθούν.
        """

        # Εδώ θα κρατήσω τα τυχών id των έργων δωρεάς που χρειάζονται ενημέρωση.
        post_ids_for_update = []

        try:
            url = "http://localhost:8000/wp-json/donationboxes/v1/updated/" + self.last_server_update
            response = requests.get(url)
        except requests.exceptions.RequestException as e:
            print(e)
            exit(1)

        print('Response code : {0}'.format(response.status_code) )

        if response.status_code == 201:  # Αν το έτοιμα πάει καλά. :) -- Προσοχή δεν σημαίνει πως πήρα και posts id --
            # self.last_server_update = datetime.datetime.now().date() # Not now, after save data on database. <<---- !
            print('Checked at {0}'.format( self.last_server_update ), end='\n\n')

            json_data = response.json()

            if json_data:  # Αν έχω δεδομένα,
                for post in json_data:
                    post_ids_for_update.append(post['ID'])
                    print(
                        'The donation project : {0} created/modified at {1}'.format(post['ID'], post['post_modified']))
                self.download_posts(post_ids_for_update)
        else:
            print('Failure to check!', end='\n\n')
            exit(1)




    def download_posts(self, donation_projects ):
        """
        Μέθοδος η οποία είναι υπεύθυνη να κατεβάσει τις απαραίτητες πληροφορίες για τα έργα δωρεάς.

        Αυτή η μέθοδος καλείτε όταν έχει εντοπιστεί πως υπάρχουν νέα έργα δωρεάς ή έργα δωρεάς τα οποία ενημερώθηκαν,
        και σκοπό έχει να κατεβάσει για αυτά, τα απαραίτητα δεδομένα που χρειάζεται το donation box.

        Προσοχή! : Δεν αποθηκεύει τα δεδομένα στη βάση δεδομένων. Απλώς τα κατεβάζει. Τίποτε άλλο.

        :param donation_projects: Μια λίστα όπου περιέχει τα ids των έργω δωρεάς προς ενημέρωση.
        :return: Τίποτα. Κατ' ουσία όμως, μεταβιβάζει σε μια άλλη μέθοδο τα δεδομένα που κατέβασε για τα έργα δωρεάς
                ώστε να τα αποθηκεύσει στη βάση δεδομένων.
        """

        all_posts = []

        url = "http://localhost:8000/wp-json/wp/v2/donationboxes/"

        for post_id in donation_projects:
            post_data = dict()
            try:
                response = requests.get(url + str(post_id) )

            except requests.exceptions.RequestException as e:
                print(e)
                exit(1)

            data = response.json()

            post_data['id']              = data['id']
            post_data['title']           = data['title']['rendered']
            post_data['modified']        = data['modified_gmt']
            post_data['content']         = data['content']['rendered']
            post_data['current_amount']  = data['project_current_amount']
            post_data['target_amount']   = data['project_target_amount']
            post_data['status']          = data['project_status']
            post_data['stylesheet_file'] = data['project_stylesheet_file']
            post_data['organizations']   = data['project_organizations']  # One or more..

            all_posts.append(post_data)

        # @Testing :
        print('\"id\": {0}'.format(all_posts[2]['id']))
        print('\"project_title\": {0}'.format(all_posts[2]['title']))
        print('\"project_modified\": {0}'.format(all_posts[2]['modified']))
        print('\"project_content\": {0}'.format(all_posts[2]['content']))
        print('\"project_current_amount\": {0}'.format(all_posts[2]['current_amount']))
        print('\"project_target_amount\": {0}'.format(all_posts[2]['target_amount']))
        print('\"project_status\": {0}'.format(all_posts[2]['status']))
        print('\"project_stylesheet_file\": {0}'.format(all_posts[2]['stylesheet_file']))
        print('\"project_organizations\": {0}'.format(all_posts[2]['organizations']))

        self.save_posts_to_database( all_posts )


    def save_posts_to_database(self, posts):
        """
        Μέθοδος η οποία είναι υπεύθυνη ώστε να αποθηκεύσει τα έργα δωρεάς στη βάση δεδομένων.

        Αυτή η μέθοδος καλείτε όταν έχουν υπάρξει νέα ή ενημερωμένα έργα δωρεάς στον κεντρικό κόμβο, έχουν κατεβεί
        *επιτυχώς* τα δεδομένα τους και πλέον είναι η ώρα να αποθηκευτούν στη τοπική βάση δεδομένων του donation box.
        Επομένως η μέθοδος αυτή συνδέετε στην τοπική βάση δεδομένων και είτε ενημερώνει τα ήδη υπάρχων έργα δωρεάς με τα
        νέα δεδομένα τους, είτε προσθέτει τα νέα έργα δωρεάς.

        :param posts: Μία λίστα όπου περιέχει τα έργα δωρεάς, κάθε έργο δωρεάς είναι ένα λεξικό.
        :return: Τίποτα. Ίσος εμφανίζω κάποιο μήνυμα - ή γράφω σε log file, ότι τα δεδομένα αποθηκεύτηκαν επιτυχώς.
        """
        pass


def main():
    db = DonationBox()
    db.update_from_server()



if __name__ == '__main__':
    main()
