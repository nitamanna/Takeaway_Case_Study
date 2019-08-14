# Takeaway_Case_Study

Case study: build a stack to get real time information online, store the data and present it graphically.



Problem Statement: 
==================

The objective of this exercise is to figure out your ability to build an infrastructure from scratch that could be usable in production. Its aim is to track the evolution of the Bitcoin (BTC) to USD exchange rate, at 10-minute intervals, and to present it visually on an interactive graph. The endpoint for the real-time data to be retrieved can be found on https://www.alphavantage.co/documentation/#crypto-exchange

We should be able to reproduce the build and run this infrastructure ourselves with the provided code. The code should be hosted on a Git repository of your choosing.

Here is the list of components that we would like to see in action. If you are totally unfamiliar with some of them, you can propose alternatives. It is expected that all of this should run in a virtual environment like Docker:

An Airflow scheduler that will run the script downloading the data, with a database backend (MySQL or PostgreSQL) located on a separate container
A script written in a language of your choice, that will be run by Airflow every 10 minutes and will retrieve the data
A time series database that will store this information
A graphical frontend to present the information
An alerting system to monitor the stack and the data





Proposed Solution & Details Implementation Steps:
=================================================

Please Download/clone/fork the below Github Repository:
https://github.com/nitamanna/Takeaway_Case_Study

Following is the Git directory structure:

/etl-pipeline
/webserver
/dbserver 


Code Walkthrough:

Pre-requisite: EC2 instance with docker installed in it.


Step -1 (Building the Docker images & Uploading into Amazon ECR)
-------------------------------------------------------------------

1. Run the below command to build the docker images from the respective directories,

	docker build . -t takeaway-airflow:latest
	docker build . -t takeaway-webserver:latest
	docker build . -t takeaway-mysql:latest

1. Create following 3 repositories into AWS ECR 

	takeaway-airflow
	takeaway-webserver
	takeaway-mysql

2. Upload the docker images into newly created repositories respectively.
	
	takeaway-airflow:latest
	takeaway-webserver:latest
	takeaway-mysql:latest
	


Step-2 (Creating ECS Fargate Task & ECS Fargate Cluster)
-----------------------------------------------------

1. Create an ECS Fargate Cluster and run the task from the cluster. 
2. Create an ECS Fargate task and add all three containers in it. 
3. Once the task is in Running status, try to invoke the cluster public IP with respective ports (mentioned during task creation) for web-server and Airflow Admin console.



Notes: 

1. In this case the required database and tables are created through the Dockerfile. The same can be done manually as well.
2. I have deployed the whole infrastructure in AWS Public Subnet as it is a demo project but the suggested architecture diagram of the power point presentation shows how it can be deployed in practical/corporate scenario
