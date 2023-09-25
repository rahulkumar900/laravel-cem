<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentModelController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RazorPayController;
use App\Http\Controllers\RequestLeadsController;
use App\Http\Controllers\TempleTransactionController;
use App\Http\Controllers\WebsitePlanController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserDataController;
/*The telesales dashboard is a centralized location where sales representatives can manage their leads and follow up with potential clients. With access to all the necessary information in one place, sales representatives can easily prioritize leads and focus on those that are most likely to convert into customers. In addition, the dashboard provides valuable insights into the performance of the sales team, allowing managers to identify areas for improvement and make data-driven decisions. Overall, the telesales dashboard is an essential tool for any company looking to streamline their sales process and improve their bottom line. */

Route::get('leads', [LeadController::class, 'index'])->name('crmleads');
/*We have been fortunate to have a growing number of clients who have viewed our subscription pages. These clients come from a diverse range of backgrounds and industries, including but not limited to finance, healthcare, technology, and education. They appreciate the variety of subscription options we offer, which cater to different needs and budgets. Some of our clients have even shared positive feedback about their experiences with our subscriptions, which has helped us refine our offerings to better meet their needs. We are always looking for ways to improve our services and attract new clients, and the interest we have received from our current client base is a testament to the quality of our subscriptions. */
Route::post('delete-lead',[LeadController::class,'deleteLead'])->name('deleteLead');
/* Delete Lead */
Route::get('unassigned-leads',[LeadController::class, 'listAllUnAssignedLeads'])->name('unassigned-leads');
/*List all  unassigned Leads.*/
Route::get('search-leads', [LeadController::class, 'searchLeadDetails'])->name('searchleads');
/*The telesales dashboard is a centralized location where sales representatives can manage their leads and follow up with potential clients. With access to all the necessary information in one place, sales representatives can easily prioritize leads and focus on those that are most likely to convert into customers. In addition, the dashboard provides valuable insights into the performance of the sales team, allowing managers to identify areas for improvement and make data-driven decisions. Overall, the telesales dashboard is an essential tool for any company looking to streamline their sales process and improve their bottom line. */
Route::get('all-leads', [LeadController::class, 'showLeadData'])->name('allleads');
/* List all Un-Assigned List Route*/
Route::get('all-unassigned-leads', [LeadController::class, 'showUnassignLeads'])->name('getAllUnAssignedLeads');
/*We have been fortunate to have a growing number of clients who have viewed our subscription pages. These clients come from a diverse range of backgrounds and industries, including but not limited to finance, healthcare, technology, and education. They appreciate the variety of subscription options we offer, which cater to different needs and budgets. Some of our clients have even shared positive feedback about their experiences with our subscriptions, which has helped us refine our offerings to better meet their needs. We are always looking for ways to improve our services and attract new clients, and the interest we have received from our current client base is a testament to the quality of our subscriptions. */
Route::get('subscription-seen', [LeadController::class, 'subSeenView'])->name('subscriptionseen');
/*We have been fortunate to have a growing number of clients who have viewed our subscription pages. These clients come from a diverse range of backgrounds and industries, including but not limited to finance, healthcare, technology, and education. They appreciate the variety of subscription options we offer, which cater to different needs and budgets. Some of our clients have even shared positive feedback about their experiences with our subscriptions, which has helped us refine our offerings to better meet their needs. We are always looking for ways to improve our services and attract new clients, and the interest we have received from our current client base is a testament to the quality of our subscriptions. */
Route::get('subscription-seen-list', [LeadController::class, 'showSubscriptionSeenData'])->name('allsubscriptionseenlist');
/*Our records show that there is a considerable list of clients who have attempted to reach us but were unable to get through to our operators. It is important that we address this issue as soon as possible in order to ensure that we are providing top-quality service to our clients. We will analyze the reasons behind the missed calls and develop a plan to improve our response time and minimize the number of missed calls. Additionally, we will consider implementing a new system that will allow clients to schedule appointments and receive a call back during a specified time window. This will help us to better manage our call volume and ensure that our clients' needs are being met. */
Route::get('all-operator-calls', [LeadController::class, 'showOperatorCallsView'])->name('alloperatorcalls');
/*
            To obtain a website plan using their IDs, first, you will need to access the website's database. Once inside, you can search for the specific IDs and retrieve their corresponding plans. It is important to ensure that the IDs are accurate and up-to-date in order to obtain the correct plan. Additionally, it may be useful to include information about the purpose and benefits of obtaining a website plan, such as ensuring efficient website management and facilitating communication between team members. Finally, it is important to consider the potential challenges that may arise during the process, such as data privacy concerns or technical difficulties, and plan accordingly to mitigate these risks.
    */
Route::get('get-crm-plans', [LeadController::class, 'leadPlans'])->name('crmleadplans');
/*
            After a thorough analysis of our sales data, it is evident that we need to improve our lead follow-up process. Studies have shown that a lead that is followed up quickly is more likely to convert into a sale. Therefore, we need to allocate more resources to this area by hiring additional staff and providing them with the necessary training. Additionally, we should implement an automated lead nurturing system to ensure that every lead receives timely and relevant communication from our sales team. This will not only increase our conversion rates, but also improve the overall customer experience.
    */
Route::post('save-followups', [LeadController::class, 'saveLeadCrmFollowup'])->name('savefollowups');
/*
            One important task to accomplish is to add new leads to the CRM system. This helps in managing customer data and keeping track of potential sales opportunities. When adding a new lead, it is important to input accurate and complete information such as their name, contact details, and any other relevant information. Additionally, you may want to consider assigning a follow-up action or task to a member of your team to ensure that the lead is contacted and engaged in a timely manner. It is also good practice to regularly review the leads in the CRM system and update their status as needed. This can help in identifying potential areas for improvement in your sales process and ultimately lead to increased conversions.
    */
Route::post('add-lead-telesales', [LeadController::class, 'addLeadTeleSales'])->name('addleadtelesales');
/*
            After a thorough analysis of our sales data, it is evident that we need to improve our lead follow-up process. Studies have shown that a lead that is followed up quickly is more likely to convert into a sale. Therefore, we need to allocate more resources to this area by hiring additional staff and providing them with the necessary training. Additionally, we should implement an automated lead nurturing system to ensure that every lead receives timely and relevant communication from our sales team. This will not only increase our conversion rates, but also improve the overall customer experience.
    */
Route::post('add-leads-followp', [LeadController::class, 'saveLeadFollowup'])->name('addleadsfollowp');
/*
            After a thorough analysis of our sales data, it is evident that we need to improve our lead follow-up process. Studies have shown that a lead that is followed up quickly is more likely to convert into a sale. Therefore, we need to allocate more resources to this area by hiring additional staff and providing them with the necessary training. Additionally, we should implement an automated lead nurturing system to ensure that every lead receives timely and relevant communication from our sales team. This will not only increase our conversion rates, but also improve the overall customer experience.
    */
Route::get('add-leads-followp', [LeadController::class, 'saveLeadFollowup'])->name('addleadsfollowpget');
/*
To obtain leads from Facebook for your customer relationship management (CRM) system, there are several steps you can take. Firstly, it is important to ensure that your Facebook page is complete and up-to-date with accurate information about your business or organization. Next, you can consider creating targeted ads on Facebook to reach potential customers who are interested in your products or services. Another option is to join and engage with relevant Facebook groups related to your industry or niche. Additionally, you can use Facebook's built-in lead generation forms to collect information from potential customers who are interested in learning more about your business. Finally, it is important to follow up with leads in a timely manner and to track your results to continually improve your lead generation efforts. By following these steps, you can effectively use Facebook to generate leads for your CRM system and ultimately grow your business.
*/
Route::get('get-facebook-leads', [LeadController::class, 'getFacebookLeads'])->name('getfacebookleads');
/*
To obtain the most up-to-date information on potential customers, it is necessary to retrieve the last requested leads from the customer relationship management system. This information will allow for a more comprehensive understanding of the market, including new trends, emerging needs, and potential opportunities. By analyzing this data, businesses can better tailor their sales and marketing strategies to fit the needs of their target audience, leading to increased revenue and customer acquisition. Additionally, this information can be used to identify areas of improvement, such as gaps in the product offering or ineffective messaging, and can inform future product development or marketing campaigns. Therefore, retrieving the last requested leads from the CRM is a crucial step in maintaining a competitive edge in the market.
*/
Route::get('get-last-requested-leads', [LeadController::class, 'getLastRequestedLeads'])->name('getlatrequestedleads');
/*
One issue that we've been encountering is that some of our leads are marked as not picked up, but they are actually incomplete. This has been causing a delay in our sales process and we need to find a solution. One potential solution could be to assign a team member to follow up with these incomplete leads and gather the missing information. Additionally, we could consider implementing a system to automatically flag leads as incomplete if they are missing certain key information. This would help ensure that all leads are properly reviewed and we can avoid any further delays in our sales process.
*/
Route::get('not-pickup-incomplete-leads', [LeadController::class, 'notPickupIncompleteLeads'])->name('notpickupincompleteleads');
/*
The user category related to Facebook query is a broad field with many different aspects to consider. One key factor is understanding the user's behavior on the platform, such as what types of content they engage with the most and how often they use the site. Additionally, it is important to analyze the user's demographic information, including age, gender, location, and interests.
To gain a deeper understanding of the user, it may also be helpful to examine their social network and connections on Facebook. This can provide insight into their relationships and affiliations, which can in turn inform marketing and advertising strategies. Finally, it is important to stay up-to-date with changes to the Facebook platform, as these can impact user behavior and preferences.
*/
Route::get('user-category-relations', [LeadController::class, 'getTemplerelation'])->name('usercategoryrelations');
/*
After the user requests leads, they should count the number of leads they have received. It is important to keep track of the number of leads in order to analyze the success of the lead generation campaign, and to make adjustments as necessary. One way to increase the number of leads is to optimize the user's website for search engines, as this can increase traffic to the site and result in more leads. Additionally, the user may consider running targeted advertising campaigns, such as social media ads or Google Ads, in order to reach a wider audience and generate more leads. By carefully analyzing the user's lead generation strategy and making adjustments as necessary, they can increase the number of leads they receive and improve their overall success rate.
*/

Route::get('count-requested-leads', [LeadController::class, 'countRequestedLeads'])->name('countrequestedleads');
/*
            Our company is currently seeking to generate more leads for our CRM system. One effective way of doing this is by requesting website leads. By collecting contact information from website visitors, we can then follow up with them and potentially convert them into customers. It is important to note that follow-up is crucial in the lead generation process, as a lack of follow-up can result in missed opportunities. Additionally, it may be worth considering implementing lead nurturing techniques, such as personalized email campaigns, in order to further engage with potential customers and increase the chances of conversion. Overall, by prioritizing lead generation and following up consistently, we can work towards growing our customer base and increasing revenue.
    */
Route::get('get-website-leads', [LeadController::class, 'getWebsiteLeads'])->name('getwebsiteleads');
Route::get('request-website-leads-data', [LeadController::class, 'requestWebsiteLeadData'])->name('requestwebsiteleadsdata');

Route::get('request-exhaust-leads-data', [LeadController::class, 'getConvertedLeads'])->name('requestexhaustleadsdata');

Route::get('not-pick-leads-data', [LeadController::class, 'leadsNotPickUp'])->name('notpickwebleads');
/*
            One possible solution to increase sales is to assign a lead to the telesales team. This would involve identifying potential customers and passing their information to the telesales team to follow up on. The telesales team could then use various techniques to engage with the customer, such as providing more information about the product, answering any questions they may have, and highlighting the benefits of the product. This approach could help to build trust and rapport with the customer, increasing the likelihood of a successful sale. Additionally, it could be useful to provide ongoing support and follow-up after the sale to ensure customer satisfaction and loyalty.
    */
Route::get('update-assign-to', [LeadController::class, 'assignToMe'])->name('updateassignto');
/*
                Upon review of the current leads for the sales funnel, it has been determined that some of the leads do not meet the criteria for potential customers. Therefore, it is necessary to reject these leads in order to focus resources on pursuing leads that have a higher likelihood of conversion. In order to ensure that the leads being pursued meet the necessary qualifications, a thorough analysis of the target market will be conducted, and the sales team will receive additional training on how to identify and target high-quality leads. This process will ultimately lead to a more efficient and effective sales strategy, resulting in increased revenue and growth for the company.
        */
Route::get('reject-lead', [LeadController::class, 'rejectLead'])->name('rejectlead');
/*
                In order to increase sales and revenue, we have previously requested leads from our customer relationship management (CRM) system. By obtaining these leads, we were able to expand our customer base and increase our chances of making sales. Moreover, the use of leads allows us to better understand our customers' needs and preferences, which in turn helps us tailor our marketing and sales strategies to better meet their needs. As such, the use of leads is crucial to the success of our business and we will continue to request them from our CRM system in the future.
        */
Route::get('requested-leads', [LeadController::class, 'requestedLeads'])->name('requestedleads');
Route::get('all-requested-fb-leads', [LeadController::class, 'allRequestedFbLeads'])->name('allrequestedfbleads');
Route::get('all-requested-web-leads', [LeadController::class, 'allRequestedWebLeads'])->name('allrequestedwebleads');
Route::get('all-requested-exhaust-leads', [LeadController::class, 'allRequestedExhaustLeads'])->name('allrequestedexhaustleads');
/*
                Today's follow-up is a crucial step in ensuring that all tasks and projects are being carried out smoothly and efficiently. By taking the time to review what has been accomplished and what still needs to be done, we can identify any potential roadblocks and come up with strategies to overcome them. Additionally, follow-up meetings provide an opportunity to discuss any new developments or concerns that may have arisen since the last meeting, allowing us to stay up-to-date and informed on all aspects of the project. It is important to take this step seriously and approach it with a proactive mindset, as it can greatly impact the success of our work.
        */
Route::get('todays-followup', [LeadController::class, 'todaysFollowup'])->name('todaysfollowup');
Route::get('todays-followup-data', [LeadController::class, 'todaysFollowupData'])->name('todaysfollowupdata');
/*
                Overall, my pending leads need to be reviewed and analyzed more carefully to identify potential opportunities for sales. It is important to take a closer look at each lead and gather more information in order to make informed decisions about which ones to pursue. It may also be beneficial to reach out to these leads with additional follow-up and personalized communication to build stronger relationships and increase the likelihood of closing a sale. Another important factor to consider is the current market conditions and trends, as well as the competitive landscape, to develop a strategic approach for converting these leads into actual sales. By taking a more thorough and strategic approach to managing these pending leads, we can maximize our potential for success and achieve our sales goals.
        */
Route::get('my-pending-leads', [LeadController::class, 'myPendingLeads'])->name('pendingleads');
Route::get('my-pending-leads-data', [LeadController::class, 'myPendingLeadsData'])->name('pendingleadsdata');
/*
            To obtain the lead details of a specific ID, you will first need to log into the system and navigate to the lead management section. Once there, you can search for the lead by entering the ID into the specified field. If the ID is valid, the system will display the relevant details, such as the lead's name, contact information, and any interactions they have had with your organization. It is important to ensure that the lead details are accurate and up-to-date, as this information will be crucial in determining the appropriate follow-up actions and next steps in the sales process.sa
    */
Route::get('get-lead-details-by-id', [LeadController::class, 'getLeadDetailsById'])->name('getleadeatailsbyid');
/*
                One way to improve the teleasales process is to ensure that there is a backup number for each lead. This can be a number that is not commonly used, so that if the primary number is not working or the lead cannot be reached, the teleasales team has an alternate way to reach out to the lead. Additionally, it may be helpful to provide the team with additional information about the lead, such as their preferred method of communication or any specific interests they have expressed in the past. This can help the team tailor their approach and increase the chances of converting the lead into a customer.
        */
Route::post('save-alternate-numbers', [LeadController::class, 'saveAlternateNumber'])->name('savealternatenumber');
/*
            To successfully manage your client relationships, it is important to stay in touch with them regularly. One way to do this is to make appointments with them to discuss their needs and how your company can help them achieve their goals. These appointments can be conducted in person, over the phone, or virtually. Additionally, it may be helpful to send follow-up emails or notes summarizing the key points discussed during the appointment, as well as any action items that were identified. By prioritizing regular communication and follow-up, you can build stronger relationships with your clients and increase the likelihood of their continued satisfaction with your services.
    */
Route::post('make-an-appointment', [AppointmentModelController::class, 'createAppointment'])->name('makeanappointment');

/*
            Telesales is a sales technique that involves contacting potential customers via telephone. The aim of telesales is to generate interest and ultimately secure sales. Overall appointments refer to the total number of scheduled meetings between a telesales agent and a potential customer. This is a crucial metric as it is directly linked to the success of a telesales campaign. Moreover, a higher number of overall appointments can lead to a greater conversion rate, as it provides more opportunities for the agent to effectively communicate the value proposition of the product or service being offered.
    */
Route::get('my-appointments', [AppointmentModelController::class, 'myAppointments'])->name('myappoitments');
Route::post('my-appointments-notes/{appointment}', [AppointmentModelController::class, 'myAppointmentNotes'])->name('addappointmentnotes');
Route::get('request-facebook-leads', [RequestLeadsController::class, 'index'])->name('requestleads');

Route::get('request-website-leads', [RequestLeadsController::class, 'requestWebsiteLeads'])->name('requestwebsiteleads');

Route::get('request-exhaust-leads', [RequestLeadsController::class, 'requestExhaust'])->name('requestexhaustleads');

Route::get('request-operator-lcall-leads', [RequestLeadsController::class, 'requestOperatorCalls'])->name('requestoperatorcalls');
/*
     To create a website plan, it is important to first consider the website's category. By understanding the website's category, you can better tailor your plan to meet the specific needs and goals of that category. For example, a website for a small business will have different requirements than a website for a large corporation. Additionally, it is important to consider the target audience of the website and how the website can best serve them. This may include features such as easy navigation, clear calls-to-action, and relevant content. By taking these factors into consideration, you can create a comprehensive website plan that will effectively meet the needs of both the website and its users.
    */
Route::get('get-crm-plans-by-category', [WebsitePlanController::class, 'getCRMPlans'])->name('getcrmplansbycategory');

/*
            To make a purchase, the client will need to be provided with a payment link that will allow them to complete the transaction easily and efficiently. It is important that the payment link is easy to use and understand, so that the client can complete the process without any complications. Once the link has been generated, it can be sent to the client via email or any other preferred communication method, along with any additional information that they may need to know about the purchase process. Providing clear and concise information to the client will help to ensure that the transaction runs smoothly and that the client is satisfied with their purchase.
    */
Route::post("generate-payment-link", [CrmController::class, 'generatePaymentLink'])->name('genpaymentlink');



#renewal & upgradation
Route::get('renewal-upgrade', [CrmController::class, 'index'])->name('renwaalnupgrade');
Route::get('all-crm-leads', [CrmController::class, 'getAllCrmLeads'])->name('allcrmleads');
Route::post('add-crm-leadsfollowp', [CrmController::class, 'updateFollowupCrm'])->name('addcrmleadsfollowp');
Route::get('search-crm-leads', [CrmController::class, 'searchCrmLeads'])->name('searchcrmleads');
Route::get('transfer-lead-to-me', [CrmController::class, 'assignLeadToSelf'])->name('transferleadtome');
Route::post('add-crm-lead-telesales', [CrmController::class, 'addLeadCrm'])->name('addcrmleadtelesales');
Route::get('add-receipts', [UserDataController::class, 'index'])->name('addreceipts');
Route::post('save-receiving-amount', [PaymentController::class, 'makePaymentManual'])->name('saverecivingamount');
/*
            In order to ensure that all failed transactions are properly handled, it is important to have a system in place that allows for easy tracking and resolution. One possible solution is to create a list of all failed transactions and assign each one to a specific day for follow-up. This will help to ensure that each transaction is properly reviewed and addressed, and will also provide a clear record of all transactions that require attention. Additionally, it may be helpful to implement automated alerts or notifications to ensure that failed transactions are addressed in a timely manner, reducing the risk of further issues or complications.
    */
Route::get('failed-transactions', [RazorPayController::class, 'index'])->name('failedtransactions');

Route::get('paytm-view', [RazorPayController::class, 'paytmView'])->name('paytmview');
Route::any('paytm-filter', [RazorPayController::class, 'paytmFilter'])->name('paytmfilterview');

Route::get('rzorpay-view', [RazorPayController::class, 'razorpayView'])
        ->name('razorpayview');
Route::get('rzorpay-filter-view', [RazorPayController::class, 'razorpayFilterView'])->name('razorfilterview');
Route::get('rzorpay-filter-view-details', [RazorPayController::class, 'viewPaymentDetailR'])->name('getrazoraydetails');
Route::get('rzorpay-filter-view-details-modal', [RazorPayController::class, 'viewPaymentDetailP'])->name('viewpaytmdetails');

Route::get('razorpay-transactions', [RazorPayController::class, 'showRzpTransactions'])->name('razorpaytransactions');
Route::get('get-paytm-transactions', [RazorPayController::class, 'showPaytmTransactions'])->name('getpaytmtransactions');
Route::get('get-paytm-failed-transactions', [RazorPayController::class, 'getPaytmFailedTxn'])->name('getpaytmfailedtransactions');

# misc / uses for all routes
Route::get('get-plan-details-by-id', [WebsitePlanController::class, 'getPlanDetailsById'])->name('getplandetailsbyid');
Route::get('get-passbook-data', [TempleTransactionController::class, 'templeTransctions'])->name('getpassbookdata');
