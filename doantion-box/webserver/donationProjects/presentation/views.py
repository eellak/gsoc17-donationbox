from django.shortcuts import render

from .models import Donation_Project
from .util import get_first_donation_project

# Create your views here.


def index(request):
    # Queries
    specific_project = get_first_donation_project
    last_project_id = Donation_Project.objects.all().last().id

    # Pass results to a dictionary
    context = { 'specific_project': specific_project,
                'last_project_id': last_project_id }

    return render( request, 'presentation/index.html', context )




def project(request, project_id ):
    """
    Επιστρέφει για ένα συγκεκριμένο donation project όλες τις εγγραφές του στη βάση δεδομένων.

    Το συγκεκριμένο view ενεργοποιείται όταν εκτλεστεί το URL pattern "http://127.0.0.1:8000/project/x", που σημαίνει
    πως ζητήται το donation project με id "x".
    Έτσι χάρης αυτό το view επιστρέφουμαι για το αιτούμενο donation project όλες τις εγγραφές που έχουμε στη βάση
    δεδομένων για αυτό.

    Προσοχή! : Αν γίνει αίτημα για ένα project_id το οποίο δεν υπάρχει τότε επιστρέφονται οι πληροφορίες για το πρώτο
    doantion project ( project_id = 1 ).
    Αν και πάλι βρισκόμαστε στην απίθατη περίπτωση, όπου η τοπική sqlite3 βάση δεδομένων μας, δεν έχει κανένα έργο
    δωρεάς μέχρι στιγμής, τότε επιστρέφει σφάλμα Http404, με ένα επρεπεί μήνυμα. :)

    :param request:
    :param project_id:
    :return:
    """

    # Queries
    try:
        specific_project = Donation_Project.objects.get( id = project_id )
    except Donation_Project.DoesNotExist:
        specific_project = get_first_donation_project


    last_project_id = Donation_Project.objects.all().last().id

    # Pass results to a dictionary
    context = { 'specific_project': specific_project,
                'last_project_id': last_project_id }

    return render( request, 'presentation/index.html', context )
