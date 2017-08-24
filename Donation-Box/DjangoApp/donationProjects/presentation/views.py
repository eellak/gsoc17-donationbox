from django.shortcuts import render

from .models import Donation_Project
from .models import temporary_Donation_Project
from django.db.models import Sum

from .util import get_first_donation_project
from .util import next_project_id
from .util import previous_project_id

import datetime




def index(request):
    """
    Presents the data of the first donation project.

    This view is enabled when it is executed the URL pattern like "http://127.0.0.1:8000".
    We respond to this request by presenting the first donation project we have in our local database.

    We also provide some information to the presentation page.
    These are :
        - current_project : The object from the database for the current donation project ( this means that we have all
                            the information for this donation project ).
        - next_project_id : The id of the next donation project.
        - previous_project_id : The id of the previous donation project.
        - total_current_amount : The total amount gathered for this donation project ( it is found in combination with
                                 local donations that have made in this donation box ).
        - total_percent : The percentage of money gathered.
        - days : The remaining days until the project expires.

    :param request: The current user request.
    :return: Presents the data of the first donation project or raise an Http404 exception if there doesn't exist
    donation projects.
    """

    # Queries
    current_project = get_first_donation_project()

    temp_amount = temporary_Donation_Project.objects.filter(project_id=current_project.id).aggregate(Sum('amount'))

    all_amount = current_project.current_amount

    if temp_amount['amount__sum'] :
        all_amount += temp_amount['amount__sum']

    percent = int( (all_amount / current_project.target_amount) * 100)

    days = current_project.end_date - datetime.date.today()

    # Pass results to a dictionary
    context = { 'current_project': current_project,
                'next_project_id': next_project_id( current_project.id ),
                'previous_project_id': previous_project_id( current_project.id ),
                'total_current_amount': all_amount,
                'total_percent': percent,
                'days': days.days }

    return render( request, 'presentation/index.html', context )




def project(request, project_id ):
    """
    Returns for the given project_id ( donation project ) all his records in the database.

    This view is enabled when it is executed the URL pattern like "http://127.0.0.1:8000/current_project/x", which means
    that requested the donation project with id "x".

    Attention! : If a request is made for an project_id that does not exist then returned information for the first
    donation project ( project_id = 1 ).
    Though again we are in the unlikely event, where the local sqlite3 database it has no donation project so far,
    then it returns an Http404 error, with a decent message. :)

    We also provide some information to the presentation page.
    These are :
        - current_project : The object from the database for the current donation project ( this means that we have all
                            the information for this donation project ).
        - next_project_id : The id of the next donation project.
        - previous_project_id : The id of the previous donation project.
        - total_current_amount : The total amount gathered for this donation project ( it is found in combination with
                                 local donations that have made in this donation box ).
        - total_percent : The percentage of money gathered.
        - days : The remaining days until the project expires.

    :param request: The current user request.
    :param project_id: The id for the requested donation project.
    :return:
    """

    # Queries
    try:
        current_project = Donation_Project.objects.get( id = project_id )
    except Donation_Project.DoesNotExist:
        current_project = get_first_donation_project()

    temp_amount = temporary_Donation_Project.objects.filter(project_id=current_project.id).aggregate(Sum('amount'))
    all_amount = current_project.current_amount

    if temp_amount['amount__sum'] :
        all_amount += temp_amount['amount__sum']

    percent = int((all_amount / current_project.target_amount) * 100)

    days = current_project.end_date - datetime.date.today()

    # Pass results to a dictionary
    context = { 'current_project': current_project,
                'next_project_id': next_project_id( current_project.id ),
                'previous_project_id': previous_project_id( current_project.id ),
                'total_current_amount': all_amount,
                'total_percent': percent,
                'days': days.days }

    return render( request, 'presentation/index.html', context )


