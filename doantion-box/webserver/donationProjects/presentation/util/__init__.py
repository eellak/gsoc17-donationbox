"""
In this source file, i place my own utility functions.
"""

from django.http import Http404

from ..models import Donation_Project


def get_first_donation_project():
    """
    Function where returns all entries for the first donation project ( of course with id = 1 )

    :return: All entries for the first donation project or raise an Http404 exception.
    """

    try:
        donation_project = Donation_Project.objects.get( id = 1 )
    except Donation_Project.DoesNotExist:
        raise Http404("Does not exist donation projects yet in the local database! :-) ")

    return donation_project
