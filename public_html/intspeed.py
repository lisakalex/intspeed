#!/home/al/.venv/bin/python3
import speedtest as st
import mysql.connector
import sys
import time
from datetime import datetime


def get_exception():
    """Helper function to work with py2.4-py3 for getting the current
    exception in a try/except block
    """
    return sys.exc_info()[1]


def get_new_speeds():
    """
    https://github.com/sivel/speedtest-cli
    """

    cnx = mysql.connector.connect(host='localhost', user='al', passwd='111', database='intspeed')
    cursor = cnx.cursor()

    speed_test = None
    start = time.time()

    dtime = datetime.now()
    # t = nowdate.strftime("%H:%M:%S")

    try:
        # speed_test = st.Speedtest()
        speed_test = st.Speedtest(secure=True)
    except Exception:
        e = get_exception()
        pass

    try:
        speed_test.get_best_server()
    except Exception:
        e = get_exception()
        pass

    try:
        ping = speed_test.results.ping
    except Exception:
        e = get_exception()
        ping = 0
        pass

    try:
        upload = speed_test.upload()
    except Exception:
        e = get_exception()
        upload = 0
        pass

    try:
        download = speed_test.download()
    except Exception:
        e = get_exception()
        download = 0
        pass

    elapsed = time.time() - start
    elapsed = round(elapsed, 2)
    # Convert download and upload speeds to megabits per second
    download_mbs = round(download / (10 ** 6), 2)
    # download_mbs = 10
    upload_mbs = round(upload / (10 ** 6), 2)
    # upload_mbs = 5

    add_hyip = "INSERT INTO speed (ping, download, upload, elapsed, dtime) VALUES (%s, %s, %s, %s, %s)"
    data_hyip = (ping, download_mbs, upload_mbs, elapsed, dtime)

    cursor.execute(add_hyip, data_hyip)
    emp_no = cursor.lastrowid
    cnx.commit()

    cursor.close()
    cnx.close()


get_new_speeds()
