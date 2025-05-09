import { __ } from '@wordpress/i18n';
import uniqueId from 'lodash-es/uniqueId';
import SoftwareOffer from './software-offer';
import SoftwareAggregateRating from './software-aggregate-rating';
import SoftwareReview from './software-review';

const id = uniqueId;
const SoftwareApplication = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_title',
		description: __('The name of the app.', 'wds'),
		required: true,
	},
	description: {
		id: id(),
		label: __('Description', 'wds'),
		type: 'TextFull',
		source: 'seo_meta',
		value: 'seo_description',
		description: __('The description of the app.', 'wds'),
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'post_data',
		value: 'post_permalink',
		description: __('The permanent URL of the app.', 'wds'),
	},
	applicationCategory: {
		id: id(),
		label: __('Application Category', 'wds'),
		type: 'Text',
		source: 'options',
		value: '',
		customSources: {
			options: {
				label: __('Application Category', 'wds'),
				values: {
					'': __('None', 'wds'),
					GameApplication: __('Game Application', 'wds'),
					SocialNetworkingApplication: __(
						'Social Networking Application',
						'wds'
					),
					TravelApplication: __(
						'Travel Application',
						'wds'
					),
					ShoppingApplication: __(
						'Shopping Application',
						'wds'
					),
					SportsApplication: __(
						'Sports Application',
						'wds'
					),
					LifestyleApplication: __(
						'Lifestyle Application',
						'wds'
					),
					BusinessApplication: __(
						'Business Application',
						'wds'
					),
					DesignApplication: __(
						'Design Application',
						'wds'
					),
					DeveloperApplication: __(
						'Developer Application',
						'wds'
					),
					DriverApplication: __(
						'Driver Application',
						'wds'
					),
					EducationalApplication: __(
						'Educational Application',
						'wds'
					),
					HealthApplication: __(
						'Health Application',
						'wds'
					),
					FinanceApplication: __(
						'Finance Application',
						'wds'
					),
					SecurityApplication: __(
						'Security Application',
						'wds'
					),
					BrowserApplication: __(
						'Browser Application',
						'wds'
					),
					CommunicationApplication: __(
						'Communication Application',
						'wds'
					),
					DesktopEnhancementApplication: __(
						'Desktop Enhancement Application',
						'wds'
					),
					EntertainmentApplication: __(
						'Entertainment Application',
						'wds'
					),
					MultimediaApplication: __(
						'Multimedia Application',
						'wds'
					),
					HomeApplication: __('Home Application', 'wds'),
					UtilitiesApplication: __(
						'Utilities Application',
						'wds'
					),
					ReferenceApplication: __(
						'Reference Application',
						'wds'
					),
				},
			},
		},
		description: __(
			'The type of app (for example, BusinessApplication or GameApplication). The value must be a supported app type.',
			'wds'
		),
	},
	operatingSystem: {
		id: id(),
		label: __('Operating System', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __(
			'The operating system(s) required to use the app (for example, Windows 7, OSX 10.6, Android 1.6).',
			'wds'
		),
		placeholder: __('E.g. Android 1.6', 'wds'),
	},
	screenshot: {
		id: id(),
		label: __('Screenshots', 'wds'),
		labelSingle: __('Screenshot', 'wds'),
		description: __('Screenshots of the app.', 'wds'),
		properties: {
			0: {
				id: id(),
				label: __('Screenshot', 'wds'),
				type: 'ImageObject',
				source: 'post_data',
				value: 'post_thumbnail',
			},
		},
	},
	offers: {
		id: id(),
		label: __('Price', 'wds'),
		description: __('Price information for the app.', 'wds'),
		properties: SoftwareOffer,
		disallowAddition: true,
	},
	aggregateRating: {
		id: id(),
		label: __('Aggregate Rating', 'wds'),
		type: 'AggregateRating',
		properties: SoftwareAggregateRating,
		description: __(
			'A nested aggregateRating of the app.',
			'wds'
		),
		required: true,
		requiredNotice: __(
			'This property is required by Google. You must include at least one of the following properties: review or aggregateRating.',
			'wds'
		),
	},
	review: {
		id: id(),
		label: __('Reviews', 'wds'),
		labelSingle: __('Review', 'wds'),
		properties: {
			0: {
				id: id(),
				type: 'Review',
				properties: SoftwareReview,
			},
		},
		description: __('Reviews of the app.', 'wds'),
		required: true,
		requiredNotice: __(
			'This property is required by Google. You must include at least one of the following properties: review or aggregateRating.',
			'wds'
		),
	},
	softwareVersion: {
		id: id(),
		label: __('Software Version', 'wds'),
		description: __('Version of the software instance.', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		placeholder: __('E.g. 1.0.1', 'wds'),
		optional: true,
	},
	releaseNotes: {
		id: id(),
		label: __('Release Notes', 'wds'),
		description: __(
			'Description of what changed in this version.',
			'wds'
		),
		type: 'Text',
		source: 'custom_text',
		value: '',
		optional: true,
	},
	downloadUrl: {
		id: id(),
		label: __('Download URL', 'wds'),
		description: __(
			'If the file can be downloaded, URL to download the binary.',
			'wds'
		),
		type: 'URL',
		source: 'custom_text',
		value: '',
		optional: true,
	},
	installUrl: {
		id: id(),
		label: __('Install URL', 'wds'),
		description: __(
			'URL at which the app may be installed, if different from the URL of the item.',
			'wds'
		),
		type: 'URL',
		source: 'custom_text',
		value: '',
		optional: true,
	},
	featureList: {
		id: id(),
		label: __('Feature List', 'wds'),
		description: __(
			'Features or modules provided by this application.',
			'wds'
		),
		type: 'Text',
		source: 'custom_text',
		value: '',
		optional: true,
	},
	fileSize: {
		id: id(),
		label: __('File Size', 'wds'),
		description: __(
			'Size of the application / package (e.g. 18MB). In the absence of a unit (MB, KB etc.), KB will be assumed.',
			'wds'
		),
		type: 'Text',
		source: 'custom_text',
		value: '',
		placeholder: __('E.g. 18MB', 'wds'),
		optional: true,
	},
	memoryRequirements: {
		id: id(),
		label: __('Memory Requirements', 'wds'),
		description: __('Minimum memory requirements.', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		optional: true,
	},
	storageRequirements: {
		id: id(),
		label: __('Storage Requirements', 'wds'),
		description: __(
			'Storage requirements (free space required).',
			'wds'
		),
		type: 'Text',
		source: 'custom_text',
		value: '',
		placeholder: __('E.g. 21MB', 'wds'),
		optional: true,
	},
	processorRequirements: {
		id: id(),
		label: __('Processor Requirements', 'wds'),
		description: __(
			'Processor architecture required to run the application (e.g. IA64).',
			'wds'
		),
		type: 'Text',
		source: 'custom_text',
		value: '',
		placeholder: __('E.g. IA64', 'wds'),
		optional: true,
	},
	softwareRequirements: {
		id: id(),
		label: __('Software Requirements', 'wds'),
		description: __(
			'Component dependency requirements for application. This includes runtime environments and shared libraries that are not included in the application distribution package, but required to run the application (Examples: DirectX, Java or .NET runtime).',
			'wds'
		),
		type: 'Text',
		source: 'custom_text',
		value: '',
		placeholder: __('E.g. DirectX', 'wds'),
		optional: true,
	},
	permissions: {
		id: id(),
		label: __('Permissions', 'wds'),
		description: __(
			'Permission(s) required to run the app (for example, a mobile app may require full internet access or may run only on wifi).',
			'wds'
		),
		type: 'Text',
		source: 'custom_text',
		value: '',
		optional: true,
	},
	applicationSuite: {
		id: id(),
		label: __('Application Suite', 'wds'),
		description: __(
			'The name of the application suite to which the application belongs (e.g. Excel belongs to Office).',
			'wds'
		),
		type: 'Text',
		source: 'custom_text',
		value: '',
		placeholder: __('E.g. Microsoft Office', 'wds'),
		optional: true,
	},
	availableOnDevice: {
		id: id(),
		label: __('Available On Device', 'wds'),
		description: __(
			'Device required to run the application. Used in cases where a specific make/model is required to run the application.',
			'wds'
		),
		type: 'Text',
		source: 'custom_text',
		value: '',
		optional: true,
	},
};
export default SoftwareApplication;
