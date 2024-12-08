# on-board-program-from-pi version of different sites
from collections import namedtuple
from enum import Enum
from config.common import SOURCE_INFO
import os

MAPS = {
11:[[12697827.294418385,2484293.052210258,1],[12697827.468434406,2484286.6882873937,1],[12697869.261165624,2484286.941769407,1],[12697869.287169414,2484293.240136117,1]],
12:[[12697844.495205812,2484286.9058716153,1],[12697844.435536966,2484272.5565622575,1],[12697853.831529474,2484272.5476816744,1],[12697853.684197098,2484287.2370093325,1]],
13:[[12697855.608261405,2484287.127774101,1],[12697855.58289169,2484273.1810213574,1],[12697866.817583416,2484273.2010985482,1],[12697866.701462382,2484287.518246738,1]],
14:[[12697844.344838375,2484285.0472883647,1],[12697869.112467743,2484285.3061404843,1],[12697869.252397798,2484279.241155142,1],[12697844.274873879,2484279.093691008,1]],
15:[[12697772.730780423,2484275.2975550652,1],[12697772.694807809,2484269.541454648,1],[12697835.983037787,2484270.1126956646,1],[12697836.037062658,2484276.258948684,1]],
16:[[12697857.537417945,2484277.834006314,1],[12697857.600001184,2484268.1367256623,1],[12697885.062672507,2484268.4290437303,1],[12697885.006299715,2484277.8395790015,1]],
17:[[12697862.469704079,2484268.2373099006,1],[12697862.72151218,2484263.4128306946,1],[12697867.973508082,2484263.4128306946,1],[12697868.009480702,2484268.5034880303,1]],
18:[[12697869.23254812,2484268.536760341,1],[12697869.304493358,2484263.379558376,1],[12697875.239968177,2484263.2797415354,1],[12697875.06010532,2484268.6698493464,1]],
19:[[12697876.21122774,2484268.5034880303,1],[12697876.283172738,2484263.1133801625,1],[12697882.902126536,2484263.1799246906,1],[12697882.974071782,2484268.7363938494,1]],
110:[[12697862.074005825,2484268.4036712167,1],[12697884.9885359,2484268.603304842,1],[12697884.95256328,2484267.5385923074,1],[12697862.002060581,2484267.1060528266,1]],
111:[[12697862.002060164,2484267.2585054557,1],[12697883.244688392,2484267.5394531963,1],[12697882.965080878,2484266.780838606,1],[12697861.986240197,2484266.5673001376,1]],
112:[[12697862.460471299,2484265.092687352,1],[12697883.05052524,2484265.429824701,1],[12697883.143728,2484263.0160507197,1],[12697862.569542186,2484263.0340047437,1]],
113:[[12697807.931940297,2484277.0563062252,1],[12697856.435642827,2484277.4354159962,1],[12697856.485190654,2484273.2192026963,1],[12697808.088803777,2484273.076908138,1]],
114:[[12697818.922462689,2484270.279153915,1],[12697818.979922097,2484267.0903874114,1],[12697729.881679142,2484266.1360199098,1],[12697729.84868768,2484269.0349349226,1]],
115:[[12697715.65658672,2484272.457162591,1],[12697715.720753644,2484266.166051763,1],[12697730.029958935,2484266.4034522255,1],[12697729.324123658,2484272.6352128885,1]],
116:[[12697687.766648715,2484276.4614744973,1],[12697722.428287713,2484276.9220413733,1],[12697722.53766827,2484270.8480584426,1],[12697687.74462772,2484270.6106579816,1]],
117:[[12697688.267627671,2484280.641167881,1],[12697697.552848708,2484280.580687541,1],[12697697.749015387,2484267.3959622863,1],[12697688.136849958,2484267.4564427584,1]],
118:[[12697699.906848349,2484280.641167881,1],[12697709.845958149,2484280.7016482237,1],[12697710.172902534,2484271.327188915,1],[12697700.16840376,2484271.024786822,1]],
119:[[12697688.006072031,2484280.883089344,1],[12697709.911346896,2484280.7016482237,1],[12697710.042124683,2484278.1009923364,1],[12697687.810160197,2484278.0405119844,1]],
120:[[12697697.548950348,2484270.896206226,1],[12697702.137173852,2484270.932954408,1],[12697702.176904406,2484268.6913205283,1],[12697697.528436048,2484268.6913205283,1]],
}

SOURCE_INFO_DICT = {
'1008110042': SOURCE_INFO(source_identifier='1008110042', x=12697861.952188, y=2484265.8015704, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110043': SOURCE_INFO(source_identifier='1008110043', x=12697868.212337, y=2484265.9312357, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110044': SOURCE_INFO(source_identifier='1008110044', x=12697869.025198, y=2484265.9528892, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110045': SOURCE_INFO(source_identifier='1008110045', x=12697875.316886, y=2484265.9492557, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110046': SOURCE_INFO(source_identifier='1008110046', x=12697876.019824, y=2484265.988661, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110047': SOURCE_INFO(source_identifier='1008110047', x=12697883.164727, y=2484266.1186273, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110048': SOURCE_INFO(source_identifier='1008110048', x=12697884.97504, y=2484267.4007071, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110049': SOURCE_INFO(source_identifier='1008110049', x=12697884.897884, y=2484278.5524532, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110061': SOURCE_INFO(source_identifier='1008110061', x=12697879.702218, y=2484278.1130032, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110059': SOURCE_INFO(source_identifier='1008110059', x=12697868.46153, y=2484278.5731486, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110002': SOURCE_INFO(source_identifier='1008110002', x=12697687.104427, y=2484276.3498801, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110005': SOURCE_INFO(source_identifier='1008110005', x=12697698.953692, y=2484277.8418479, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110004': SOURCE_INFO(source_identifier='1008110004', x=12697698.965729, y=2484276.7836234, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1008110013': SOURCE_INFO(source_identifier='1008110013', x=12697711.938496, y=2484276.8319056, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008120001': SOURCE_INFO(source_identifier='1008120001', x=12697684.731185, y=2484269.5200455, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110006': SOURCE_INFO(source_identifier='1008110006', x=12697698.425327, y=2484265.734528, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110001': SOURCE_INFO(source_identifier='1008110001', x=12697687.169809, y=2484269.0502705, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110007': SOURCE_INFO(source_identifier='1008110007', x=12697702.281181, y=2484270.9878963, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110009': SOURCE_INFO(source_identifier='1008110009', x=12697708.529216, y=2484271.0358987, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110011': SOURCE_INFO(source_identifier='1008110011', x=12697714.755145, y=2484271.1048635, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110003': SOURCE_INFO(source_identifier='1008110003', x=12697687.049092, y=2484280.4615476, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110008': SOURCE_INFO(source_identifier='1008110008', x=12697702.606595, y=2484271.014194, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110010': SOURCE_INFO(source_identifier='1008110010', x=12697708.851163, y=2484271.0659176, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110012': SOURCE_INFO(source_identifier='1008110012', x=12697715.078652, y=2484271.111902, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008120002': SOURCE_INFO(source_identifier='1008120002', x=12697714.692393, y=2484269.0573079, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110057': SOURCE_INFO(source_identifier='1008110057', x=12697866.911684, y=2484278.5351117, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110055': SOURCE_INFO(source_identifier='1008110055', x=12697855.458546, y=2484278.4211632, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1008110053': SOURCE_INFO(source_identifier='1008110053', x=12697853.887484, y=2484278.4211632, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110056': SOURCE_INFO(source_identifier='1008110056', x=12697855.422247, y=2484285.836353, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110058': SOURCE_INFO(source_identifier='1008110058', x=12697866.840455, y=2484285.9459078, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110060': SOURCE_INFO(source_identifier='1008110060', x=12697868.391211, y=2484285.9608872, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110054': SOURCE_INFO(source_identifier='1008110054', x=12697853.95285, y=2484285.8283173, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110052': SOURCE_INFO(source_identifier='1008110052', x=12697844.237952, y=2484286.0381227, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110051': SOURCE_INFO(source_identifier='1008110051', x=12697844.318827, y=2484278.1088788, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110068': SOURCE_INFO(source_identifier='1008110068', x=12697847.793542, y=2484292.6991896, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110067': SOURCE_INFO(source_identifier='1008110067', x=12697837.70903, y=2484292.647466, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110065': SOURCE_INFO(source_identifier='1008110065', x=12697827.356252, y=2484292.583601, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110066': SOURCE_INFO(source_identifier='1008110066', x=12697837.403482, y=2484292.6525659, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110069': SOURCE_INFO(source_identifier='1008110069', x=12697848.112025, y=2484292.702989, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110062': SOURCE_INFO(source_identifier='1008110062', x=12697879.613209, y=2484286.7422964, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110070': SOURCE_INFO(source_identifier='1008110070', x=12697852.800096, y=2484297.4887846, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110035': SOURCE_INFO(source_identifier='1008110035', x=12697836.829458, y=2484272.6588379, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1008110037': SOURCE_INFO(source_identifier='1008110037', x=12697843.320307, y=2484272.7026599, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110038': SOURCE_INFO(source_identifier='1008110038', x=12697846.565731, y=2484272.7464818, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110040': SOURCE_INFO(source_identifier='1008110040', x=12697853.051531, y=2484272.8122147, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110041': SOURCE_INFO(source_identifier='1008110041', x=12697856.316568, y=2484272.7533175, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110031': SOURCE_INFO(source_identifier='1008110031', x=12697822.244003, y=2484278.5157154, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110032': SOURCE_INFO(source_identifier='1008110032', x=12697828.757861, y=2484277.6856072, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1008110050': SOURCE_INFO(source_identifier='1008110050', x=12697841.692703, y=2484277.7500552, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110030': SOURCE_INFO(source_identifier='1008110030', x=12697815.716445, y=2484277.6542705, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1008110033': SOURCE_INFO(source_identifier='1008110033', x=12697834.111003, y=2484266.676889, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110028': SOURCE_INFO(source_identifier='1008110028', x=12697816.447631, y=2484266.5864645, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110027': SOURCE_INFO(source_identifier='1008110027', x=12697809.710507, y=2484266.5184512, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110026': SOURCE_INFO(source_identifier='1008110026', x=12697802.969265, y=2484266.479375, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110025': SOURCE_INFO(source_identifier='1008110025', x=12697796.208372, y=2484266.4337249, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110024': SOURCE_INFO(source_identifier='1008110024', x=12697789.47296, y=2484266.3757525, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110023': SOURCE_INFO(source_identifier='1008110023', x=12697782.71978, y=2484266.3331966, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110022': SOURCE_INFO(source_identifier='1008110022', x=12697775.982007, y=2484266.2798154, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110021': SOURCE_INFO(source_identifier='1008110021', x=12697769.248297, y=2484266.2204528, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110020': SOURCE_INFO(source_identifier='1008110020', x=12697762.470892, y=2484266.1861757, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110019': SOURCE_INFO(source_identifier='1008110019', x=12697755.717877, y=2484266.1252807, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110018': SOURCE_INFO(source_identifier='1008110018', x=12697748.98386, y=2484266.07892, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110017': SOURCE_INFO(source_identifier='1008110017', x=12697742.242326, y=2484266.0582378, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110016': SOURCE_INFO(source_identifier='1008110016', x=12697735.494465, y=2484265.9720316, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110015': SOURCE_INFO(source_identifier='1008110015', x=12697728.762803, y=2484265.9316884, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110014': SOURCE_INFO(source_identifier='1008110014', x=12697721.976002, y=2484265.889364, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1008110029': SOURCE_INFO(source_identifier='1008110029', x=12697789.852792, y=2484276.5121732, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1008110034': SOURCE_INFO(source_identifier='1008110034', x=12697836.515348, y=2484272.646676, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1008110036': SOURCE_INFO(source_identifier='1008110036', x=12697842.994941, y=2484272.6936007, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1008110039': SOURCE_INFO(source_identifier='1008110039', x=12697852.724625, y=2484272.8064426, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008120003': SOURCE_INFO(source_identifier='1008120003', x=12697846.209394, y=2484270.0120755, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008120006': SOURCE_INFO(source_identifier='1008120006', x=12697838.157427, y=2484281.9257835, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008120005': SOURCE_INFO(source_identifier='1008120005', x=12697891.583493, y=2484272.7588377, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110063': SOURCE_INFO(source_identifier='1008110063', x=12697828.746269, y=2484286.2668678, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008110064': SOURCE_INFO(source_identifier='1008110064', x=12697841.624872, y=2484286.3906273, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008120007': SOURCE_INFO(source_identifier='1008120007', x=12697829.483742, y=2484294.7300582, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1008120008': SOURCE_INFO(source_identifier='1008120008', x=12697838.132503, y=2484294.8317376, z=1, type='non-lon-lat', activated=True, addition_info={}),
}


# [data_pool]
# maximum time range inside a current data pool
window_size_in_sec = 2.
# time gap between two updates in data pool(second)
sec_per_update = 1

# [TRACKING]
CAL_PERIOD_SEC = 1.8  # time gap between two calculations (second)

ALARM_PARAMS = {
	'ALARM_PIN': 17,

	# rule 1: alarm |= within(pos, ALARM_ZONE) and recent(vel_history) > ALARM_VELOCITY_THRESHOLD_HIGHER
	'ALARM_VELOCITY_THRESHOLD_HIGHER': 1.7,
	'ALARM_TOLERANCE_NUMBER': 5,

	# rule 2: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_HIGHER
	'STEP_ALARM_THRESHOLD_HIGHER': 13,

	# rule 3: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_LOWER and abs(vel) >ALARM_VELOCITY_THRESHOLD_LOWER
	'ALARM_VELOCITY_THRESHOLD_LOWER': 1.55,
	'STEP_ALARM_THRESHOLD_LOWER': 10,

	'ALARM_ZONE': [polygon for polyid, polygon in MAPS.items()],  # it can't be empty list or None.
}

FILTER_MODULE_PARAMS = {
	'RSSI_FILTER_ALGO_FILE': "avg_filter.py",
	'RSSI_THRESHOLD': -83,
}

LOCALIZATION_MODULE_PARAMS = {
	'LOCALIZATION_ALGO_FILE': "new_localization_pf.cpython-37m-arm-linux-gnueabihf.so",
	'num_of_particles': 700,
	'max_vel': 1.8,  # maximum speed of human, unit in m/s
	'obs_model_mean': [-73.0656, 1.6871],  # mean and variance of P(rssi, distance)
	'obs_model_var': [[51.171, -5.218], [-5.218, 2.912]],
	'loss_model_range': [-120, -110],
	'weight_observe': 0.8,  # mean and variance of P(rssi, distance)
	'weight_loss': 1 - 0.8,
	'grid_size': 0.5,
	'cal_fq': CAL_PERIOD_SEC,
}

VELOCITY_MODULE_PARAMS = {
	'VELOCITY_ALGO_FILE': "analyse_velocity.py",
	'VELOCITY_LEAST_SQUARE_WINDOW': 12,  # maximum num of position used to calculate velocity via least square
	'LOCALIZATION_CAL_PERIOD': CAL_PERIOD_SEC,
}

if __name__ == "__main__":
    # test for configs
    pass
