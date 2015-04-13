#!/usr/bin/env python
import os
from optparse import OptionParser
from datetime import date, timedelta, datetime

class Siivooja(object):
    path =  '/srv/backup'
    verbose = False
    days = 14

    def __init__(self, path = None, verbose = False, days = 14):
        if path:
            self.path = path
        if verbose:
            self.verbose = verbose
        if days:
            self.days = int(days)

    def scan_and_remove(self):
        files = os.listdir(self.path)
        for file in files:
            if file.endswith('.sql.gz'):
                data = self.parse_filename(file)
                if(self.is_removable(data['date'])):
                    self.remove(file)

    def parse_filename(self, filename):
        tokens  = filename.split('-')
        first = tokens[0] if(tokens[0])  else ''
        second = tokens[1] if(tokens[1])  else ''
        datetime_plus_ext = second.split('.')
        date = "%s %s" % (datetime_plus_ext[0], datetime_plus_ext[1])
        return { 'db_name': first, 'date': date }


    def is_removable(self, date):
        created_date = datetime.strptime(date, '%Y%m%d %H%M%S')
        now = datetime.now()
        delta = now - created_date
        if(self.verbose):
            print "Date %s - delta days: %d" % (created_date.strftime("%Y-%m-%d %H:%M:%S") , delta.days)
            print (delta.days >= self.days)
        return (delta.days >= self.days)


    def remove(self, file):
        fullpath = "%s/%s" % (self.path, file)
        os.remove(fullpath)
        if(self.verbose):
            print "Deleted %s \n" % fullpath

if __name__ == "__main__":
    parser = OptionParser()
    parser.add_option("-p", "--path", dest="path",
                      help="Specify a path to scan for .sql.gz files", metavar="path")
    parser.add_option("-d", "--days", dest="days",
                      help="Remove files which were created X days ago", metavar="14")
    parser.add_option("-v", "--verbose",  dest="verbose", default=False,
                      help="Show debug information", metavar="True")

    (options, args) = parser.parse_args()

    siivooja = Siivooja(options.path, options.verbose, options.days)
    siivooja.scan_and_remove()


