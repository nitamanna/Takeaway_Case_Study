import datetime as dt
import urllib.request 
import json
import os

import airflow
from airflow.utils.email import send_email
from airflow import DAG
from airflow.hooks.mysql_hook import MySqlHook
from airflow.operators.python_operator import PythonOperator



def notify_email(contextDict, **kwargs):
    """
	Send custom email alerts.
    """
    # email title.
    title = "Airflow alert: {task_name} Failed".format(**contextDict)
   
    # email contents
    body = """
    Hi Everyone, <br>
    <br>
    There's been an error in the {task_name} job.<br>
    <br>
    Forever yours,<br>
    Airflow bot <br>
    """.format(**contextDict)
   
    send_email(os.environ['EMAIL_ADDRESS'], title, body)


def extract_BitcoinRate(**kwargs):
    print(os.environ)
    webUrl  = urllib.request.urlopen(os.environ['SOURCE_URL'])
    return webUrl.read()


def transform_BitcoinRate(**kwargs):
    ti=kwargs['ti']

    parsed_records=ti.xcom_pull(key=None, task_ids='extract_BitcoinRate')
    parsed_dict=json.loads(parsed_records)

    print('Printing Task 1 Values in extract_BitcoinRate')
    
    From_Curr_Code=parsed_dict["Realtime Currency Exchange Rate"]["1. From_Currency Code"]
    From_Curr_Name=parsed_dict["Realtime Currency Exchange Rate"]["2. From_Currency Name"]
    To_Curr_Code=parsed_dict["Realtime Currency Exchange Rate"]["3. To_Currency Code"]
    To_Curr_Name=parsed_dict["Realtime Currency Exchange Rate"]["4. To_Currency Name"]
    Exchange_Rate=parsed_dict["Realtime Currency Exchange Rate"]["5. Exchange Rate"]
    Last_Refresh_Dttm=parsed_dict["Realtime Currency Exchange Rate"]["6. Last Refreshed"]

    sql="INSERT INTO BitcoinExchangeRate(Last_Refresh_Dttm,From_Curr_Code,From_Curr_Name,To_Curr_Code,To_Curr_Name,Exchange_Rate) VALUES ('%s', '%s', '%s', '%s', '%s', %s)" %(Last_Refresh_Dttm,From_Curr_Code,From_Curr_Name,To_Curr_Code,To_Curr_Name,Exchange_Rate)

    return sql


def load_BitcoinRate(**kwargs):
    ti = kwargs['ti']
    sql=ti.xcom_pull(key=None, task_ids='transform_BitcoinRate')
    connection = MySqlHook(mysql_conn_id=os.environ['AIRFLOW_CONN_ID'])
    connection.run(sql, autocommit=True)

    return True



default_args = {
    'max_active_runs' : 1,
    'owner': 'airflow',
    'start_date' : airflow.utils.dates.days_ago(0),
    'concurrency': 1,
    'email_on_failure': True,
    'retries': 0
}

with DAG('bitcoinexchangerate_dag',
        default_args=default_args,
        schedule_interval='*/10 * * * *',
        ) as dag:

    opr_extract_BitcoinRate=PythonOperator(task_id='extract_BitcoinRate',
                            python_callable=extract_BitcoinRate, 
			    provide_context=True,
			    on_failure_callback=notify_email)

    opr_transform_BitcoinRate=PythonOperator(task_id='transform_BitcoinRate',
                            python_callable=transform_BitcoinRate, 
			    provide_context=True,
			    on_failure_callback=notify_email)

    opr_load_BitcoinRate=PythonOperator(task_id='load_BitcoinRate',
                            python_callable=load_BitcoinRate, 
			    provide_context=True,
			    on_failure_callback=notify_email)

opr_extract_BitcoinRate >> opr_transform_BitcoinRate >> opr_load_BitcoinRate

