<?php
namespace Aws\Rekognition;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Rekognition** service.
 * @method \Aws\Result associateFaces(array $args = [])
 * @method \GuzzleHttp\Promise\Promise associateFacesAsync(array $args = [])
 * @method \Aws\Result compareFaces(array $args = [])
 * @method \GuzzleHttp\Promise\Promise compareFacesAsync(array $args = [])
 * @method \Aws\Result copyProjectVersion(array $args = [])
 * @method \GuzzleHttp\Promise\Promise copyProjectVersionAsync(array $args = [])
 * @method \Aws\Result createCollection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createCollectionAsync(array $args = [])
 * @method \Aws\Result createDataset(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createDatasetAsync(array $args = [])
 * @method \Aws\Result createFaceLivenessSession(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createFaceLivenessSessionAsync(array $args = [])
 * @method \Aws\Result createProject(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createProjectAsync(array $args = [])
 * @method \Aws\Result createProjectVersion(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createProjectVersionAsync(array $args = [])
 * @method \Aws\Result createStreamProcessor(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createStreamProcessorAsync(array $args = [])
 * @method \Aws\Result createUser(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createUserAsync(array $args = [])
 * @method \Aws\Result deleteCollection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteCollectionAsync(array $args = [])
 * @method \Aws\Result deleteDataset(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteDatasetAsync(array $args = [])
 * @method \Aws\Result deleteFaces(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteFacesAsync(array $args = [])
 * @method \Aws\Result deleteProject(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteProjectAsync(array $args = [])
 * @method \Aws\Result deleteProjectPolicy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteProjectPolicyAsync(array $args = [])
 * @method \Aws\Result deleteProjectVersion(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteProjectVersionAsync(array $args = [])
 * @method \Aws\Result deleteStreamProcessor(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteStreamProcessorAsync(array $args = [])
 * @method \Aws\Result deleteUser(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteUserAsync(array $args = [])
 * @method \Aws\Result describeCollection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeCollectionAsync(array $args = [])
 * @method \Aws\Result describeDataset(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeDatasetAsync(array $args = [])
 * @method \Aws\Result describeProjectVersions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeProjectVersionsAsync(array $args = [])
 * @method \Aws\Result describeProjects(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeProjectsAsync(array $args = [])
 * @method \Aws\Result describeStreamProcessor(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeStreamProcessorAsync(array $args = [])
 * @method \Aws\Result detectCustomLabels(array $args = [])
 * @method \GuzzleHttp\Promise\Promise detectCustomLabelsAsync(array $args = [])
 * @method \Aws\Result detectFaces(array $args = [])
 * @method \GuzzleHttp\Promise\Promise detectFacesAsync(array $args = [])
 * @method \Aws\Result detectLabels(array $args = [])
 * @method \GuzzleHttp\Promise\Promise detectLabelsAsync(array $args = [])
 * @method \Aws\Result detectModerationLabels(array $args = [])
 * @method \GuzzleHttp\Promise\Promise detectModerationLabelsAsync(array $args = [])
 * @method \Aws\Result detectProtectiveEquipment(array $args = [])
 * @method \GuzzleHttp\Promise\Promise detectProtectiveEquipmentAsync(array $args = [])
 * @method \Aws\Result detectText(array $args = [])
 * @method \GuzzleHttp\Promise\Promise detectTextAsync(array $args = [])
 * @method \Aws\Result disassociateFaces(array $args = [])
 * @method \GuzzleHttp\Promise\Promise disassociateFacesAsync(array $args = [])
 * @method \Aws\Result distributeDatasetEntries(array $args = [])
 * @method \GuzzleHttp\Promise\Promise distributeDatasetEntriesAsync(array $args = [])
 * @method \Aws\Result getCelebrityInfo(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getCelebrityInfoAsync(array $args = [])
 * @method \Aws\Result getCelebrityRecognition(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getCelebrityRecognitionAsync(array $args = [])
 * @method \Aws\Result getContentModeration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getContentModerationAsync(array $args = [])
 * @method \Aws\Result getFaceDetection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getFaceDetectionAsync(array $args = [])
 * @method \Aws\Result getFaceLivenessSessionResults(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getFaceLivenessSessionResultsAsync(array $args = [])
 * @method \Aws\Result getFaceSearch(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getFaceSearchAsync(array $args = [])
 * @method \Aws\Result getLabelDetection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getLabelDetectionAsync(array $args = [])
 * @method \Aws\Result getMediaAnalysisJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getMediaAnalysisJobAsync(array $args = [])
 * @method \Aws\Result getPersonTracking(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getPersonTrackingAsync(array $args = [])
 * @method \Aws\Result getSegmentDetection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getSegmentDetectionAsync(array $args = [])
 * @method \Aws\Result getTextDetection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getTextDetectionAsync(array $args = [])
 * @method \Aws\Result indexFaces(array $args = [])
 * @method \GuzzleHttp\Promise\Promise indexFacesAsync(array $args = [])
 * @method \Aws\Result listCollections(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listCollectionsAsync(array $args = [])
 * @method \Aws\Result listDatasetEntries(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listDatasetEntriesAsync(array $args = [])
 * @method \Aws\Result listDatasetLabels(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listDatasetLabelsAsync(array $args = [])
 * @method \Aws\Result listFaces(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listFacesAsync(array $args = [])
 * @method \Aws\Result listMediaAnalysisJobs(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listMediaAnalysisJobsAsync(array $args = [])
 * @method \Aws\Result listProjectPolicies(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listProjectPoliciesAsync(array $args = [])
 * @method \Aws\Result listStreamProcessors(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listStreamProcessorsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result listUsers(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listUsersAsync(array $args = [])
 * @method \Aws\Result putProjectPolicy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putProjectPolicyAsync(array $args = [])
 * @method \Aws\Result recognizeCelebrities(array $args = [])
 * @method \GuzzleHttp\Promise\Promise recognizeCelebritiesAsync(array $args = [])
 * @method \Aws\Result searchFaces(array $args = [])
 * @method \GuzzleHttp\Promise\Promise searchFacesAsync(array $args = [])
 * @method \Aws\Result searchFacesByImage(array $args = [])
 * @method \GuzzleHttp\Promise\Promise searchFacesByImageAsync(array $args = [])
 * @method \Aws\Result searchUsers(array $args = [])
 * @method \GuzzleHttp\Promise\Promise searchUsersAsync(array $args = [])
 * @method \Aws\Result searchUsersByImage(array $args = [])
 * @method \GuzzleHttp\Promise\Promise searchUsersByImageAsync(array $args = [])
 * @method \Aws\Result startCelebrityRecognition(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startCelebrityRecognitionAsync(array $args = [])
 * @method \Aws\Result startContentModeration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startContentModerationAsync(array $args = [])
 * @method \Aws\Result startFaceDetection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startFaceDetectionAsync(array $args = [])
 * @method \Aws\Result startFaceSearch(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startFaceSearchAsync(array $args = [])
 * @method \Aws\Result startLabelDetection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startLabelDetectionAsync(array $args = [])
 * @method \Aws\Result startMediaAnalysisJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startMediaAnalysisJobAsync(array $args = [])
 * @method \Aws\Result startPersonTracking(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startPersonTrackingAsync(array $args = [])
 * @method \Aws\Result startProjectVersion(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startProjectVersionAsync(array $args = [])
 * @method \Aws\Result startSegmentDetection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startSegmentDetectionAsync(array $args = [])
 * @method \Aws\Result startStreamProcessor(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startStreamProcessorAsync(array $args = [])
 * @method \Aws\Result startTextDetection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startTextDetectionAsync(array $args = [])
 * @method \Aws\Result stopProjectVersion(array $args = [])
 * @method \GuzzleHttp\Promise\Promise stopProjectVersionAsync(array $args = [])
 * @method \Aws\Result stopStreamProcessor(array $args = [])
 * @method \GuzzleHttp\Promise\Promise stopStreamProcessorAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateDatasetEntries(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateDatasetEntriesAsync(array $args = [])
 * @method \Aws\Result updateStreamProcessor(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateStreamProcessorAsync(array $args = [])
 */
class RekognitionClient extends AwsClient {}