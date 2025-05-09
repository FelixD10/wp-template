<?php
namespace Aws\DevOpsGuru;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon DevOps Guru** service.
 * @method \Aws\Result addNotificationChannel(array $args = [])
 * @method \GuzzleHttp\Promise\Promise addNotificationChannelAsync(array $args = [])
 * @method \Aws\Result deleteInsight(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteInsightAsync(array $args = [])
 * @method \Aws\Result describeAccountHealth(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeAccountHealthAsync(array $args = [])
 * @method \Aws\Result describeAccountOverview(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeAccountOverviewAsync(array $args = [])
 * @method \Aws\Result describeAnomaly(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeAnomalyAsync(array $args = [])
 * @method \Aws\Result describeEventSourcesConfig(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeEventSourcesConfigAsync(array $args = [])
 * @method \Aws\Result describeFeedback(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeFeedbackAsync(array $args = [])
 * @method \Aws\Result describeInsight(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeInsightAsync(array $args = [])
 * @method \Aws\Result describeOrganizationHealth(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeOrganizationHealthAsync(array $args = [])
 * @method \Aws\Result describeOrganizationOverview(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeOrganizationOverviewAsync(array $args = [])
 * @method \Aws\Result describeOrganizationResourceCollectionHealth(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeOrganizationResourceCollectionHealthAsync(array $args = [])
 * @method \Aws\Result describeResourceCollectionHealth(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeResourceCollectionHealthAsync(array $args = [])
 * @method \Aws\Result describeServiceIntegration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeServiceIntegrationAsync(array $args = [])
 * @method \Aws\Result getCostEstimation(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getCostEstimationAsync(array $args = [])
 * @method \Aws\Result getResourceCollection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getResourceCollectionAsync(array $args = [])
 * @method \Aws\Result listAnomaliesForInsight(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listAnomaliesForInsightAsync(array $args = [])
 * @method \Aws\Result listAnomalousLogGroups(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listAnomalousLogGroupsAsync(array $args = [])
 * @method \Aws\Result listEvents(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listEventsAsync(array $args = [])
 * @method \Aws\Result listInsights(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listInsightsAsync(array $args = [])
 * @method \Aws\Result listMonitoredResources(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listMonitoredResourcesAsync(array $args = [])
 * @method \Aws\Result listNotificationChannels(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listNotificationChannelsAsync(array $args = [])
 * @method \Aws\Result listOrganizationInsights(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listOrganizationInsightsAsync(array $args = [])
 * @method \Aws\Result listRecommendations(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listRecommendationsAsync(array $args = [])
 * @method \Aws\Result putFeedback(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putFeedbackAsync(array $args = [])
 * @method \Aws\Result removeNotificationChannel(array $args = [])
 * @method \GuzzleHttp\Promise\Promise removeNotificationChannelAsync(array $args = [])
 * @method \Aws\Result searchInsights(array $args = [])
 * @method \GuzzleHttp\Promise\Promise searchInsightsAsync(array $args = [])
 * @method \Aws\Result searchOrganizationInsights(array $args = [])
 * @method \GuzzleHttp\Promise\Promise searchOrganizationInsightsAsync(array $args = [])
 * @method \Aws\Result startCostEstimation(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startCostEstimationAsync(array $args = [])
 * @method \Aws\Result updateEventSourcesConfig(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateEventSourcesConfigAsync(array $args = [])
 * @method \Aws\Result updateResourceCollection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateResourceCollectionAsync(array $args = [])
 * @method \Aws\Result updateServiceIntegration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateServiceIntegrationAsync(array $args = [])
 */
class DevOpsGuruClient extends AwsClient {}