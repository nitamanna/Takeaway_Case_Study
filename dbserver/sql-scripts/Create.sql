
CREATE TABLE DEMOPROJECT.BitcoinExchangeRate ( 
Record_No INT AUTO_INCREMENT, 
From_Curr_Code CHAR(3), 
From_Curr_Name VARCHAR(30), 
To_Curr_Code CHAR(3), 
To_Curr_Name VARCHAR(30), 
Exchange_Rate DECIMAL(15,5), 
Last_Refresh_Dttm TIMESTAMP, 
PRIMARY KEY (Record_No), 
UNIQUE KEY (Last_Refresh_Dttm) );

CREATE TABLE DEMOPROJECT.THRESHOLD (
Record_No INT default 1, 
Min_Threshold DECIMAL(15,5), 
Max_Threshold DECIMAL(15,5),  
Submitted_on TIMESTAMP, 
PRIMARY KEY (Record_No));

CREATE USER 'takeaway' IDENTIFIED BY 'takeaway';

grant all privileges on DEMOPROJECT.* to takeaway;
