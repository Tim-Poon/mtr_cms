# on-board-program-from-pi version of different sites
from collections import namedtuple
from enum import Enum
from config.common import SOURCE_INFO
import os

MAPS = {
    11:[[12695018.0761481,2481901.1979400725,1],[12695024.466271468,2481902.479725721,1],[12695024.096731653,2481904.2384019187,1],[12695017.714532219,2481902.8584890086,1]],
    12:[[12695032.673921723,2481861.9047121983,1],[12695034.679590631,2481862.3004987375,1],[12695040.374194838,2481836.2031045873,1],[12695038.262750428,2481835.82343058,1]],
    13:[[12695031.431021206,2481860.979035258,1],[12695034.711348439,2481861.634769024,1],[12695026.354052396,2481900.05243434,1],[12695023.073725171,2481899.396702309,1]],
    14:[[12695023.030514283,2481899.4257951267,1],[12695022.438256433,2481902.105258409,1],[12695024.410460496,2481902.4598930967,1],[12695024.997241702,2481899.806493299,1]],
    15:[[12695027.940800177,2481892.3449938265,1],[12695042.583602713,2481895.0336826947,1],[12695041.67661916,2481899.350109356,1],[12695026.963757522,2481896.5068686376,1]],
    16:[[12695040.579415333,2481883.3355305647,1],[12695038.215881068,2481894.1553221415,1],[12695042.693815257,2481895.1271181107,1],[12695044.85730766,2481884.1880616,1]],
    17:[[12695042.680494932,2481883.954597521,1],[12695044.016436819,2481869.232694383,1],[12695046.646596657,2481869.7220666567,1],[12695045.189385721,2481884.958706238,1]],
    18:[[12695046.868385602,2481856.118659902,1],[12695043.797067357,2481870.2584808827,1],[12695045.704494337,2481870.6408778853,1],[12695048.588225348,2481856.4524055505,1]],
    19:[[12695050.463776698,2481847.061271944,1],[12695053.241027752,2481847.572903847,1],[12695050.433859732,2481860.4079285297,1],[12695047.72520902,2481859.893222641,1]],
    110:[[12695055.072514717,2481832.200395693,1],[12695051.743585676,2481847.505096113,1],[12695053.341062022,2481847.889266909,1],[12695056.489744602,2481832.4973710203,1]],
    111:[[12695052.960468538,2481831.798584778,1],[12695055.95633629,2481832.524317126,1],[12695064.548409674,2481791.191822614,1],[12695061.585991103,2481790.5710569476,1]],
    112:[[12695067.496599423,2481752.328415026,1],[12695052.871907031,2481819.666676423,1],[12695056.742484799,2481820.3011154286,1],[12695071.046094008,2481752.923250562,1]],
    113:[[12695055.161991445,2481748.7104082364,1],[12695071.124765951,2481751.9474984556,1],[12695069.974475058,2481756.415195564,1],[12695054.24858612,2481753.0339472955,1]],
    114:[[12695059.902581835,2481728.0274419365,1],[12695055.393300943,2481748.9613427757,1],[12695059.98655259,2481750.064992262,1],[12695064.314874653,2481729.016771339,1]],
    115:[[12695066.682369327,2481728.01084851,1],[12695065.554185916,2481733.0676807566,1],[12695063.347869806,2481732.506643854,1],[12695064.479665263,2481727.5303833154,1]],
}

SOURCE_INFO_DICT = {
    '1004110023': SOURCE_INFO(source_identifier='1004110023', x=12695064.943294, y=2481733.1088643, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110011': SOURCE_INFO(source_identifier='1004110011', x=12695064.945359, y=2481733.9933918, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110108': SOURCE_INFO(source_identifier='1004110108', x=12695063.889335, y=2481764.7652997, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110110': SOURCE_INFO(source_identifier='1004110110', x=12695065.519404, y=2481757.2418527, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110019': SOURCE_INFO(source_identifier='1004110019', x=12695070.624959, y=2481755.2024698, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004120002': SOURCE_INFO(source_identifier='1004120002', x=12695073.089484, y=2481755.7212056, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110021': SOURCE_INFO(source_identifier='1004110021', x=12695070.081758, y=2481757.3357738, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110020': SOURCE_INFO(source_identifier='1004110020', x=12695070.547846, y=2481755.4690491, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004120003': SOURCE_INFO(source_identifier='1004120003', x=12695071.93518, y=2481760.8893486, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004120004': SOURCE_INFO(source_identifier='1004120004', x=12695070.735521, y=2481766.4319003, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110109': SOURCE_INFO(source_identifier='1004110109', x=12695069.332309, y=2481761.083268, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004120005': SOURCE_INFO(source_identifier='1004120005', x=12695069.624214, y=2481771.8557908, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110111': SOURCE_INFO(source_identifier='1004110111', x=12695065.263703, y=2481756.651303, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110107': SOURCE_INFO(source_identifier='1004110107', x=12695062.257598, y=2481772.2645885, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110106': SOURCE_INFO(source_identifier='1004110106', x=12695060.682974, y=2481779.7567836, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110105': SOURCE_INFO(source_identifier='1004110105', x=12695059.039922, y=2481787.2673806, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110104': SOURCE_INFO(source_identifier='1004110104', x=12695057.428143, y=2481794.7835974, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110055': SOURCE_INFO(source_identifier='1004110055', x=12695047.859035, y=2481861.4445348, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110056': SOURCE_INFO(source_identifier='1004110056', x=12695047.798056, y=2481861.6443697, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110057': SOURCE_INFO(source_identifier='1004110057', x=12695047.025721, y=2481865.229623, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004120007': SOURCE_INFO(source_identifier='1004120007', x=12695048.728287, y=2481867.7007695, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110059': SOURCE_INFO(source_identifier='1004110059', x=12695046.257973, y=2481868.9737999, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110100': SOURCE_INFO(source_identifier='1004110100', x=12695038.59482, y=2481885.3092146, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110097': SOURCE_INFO(source_identifier='1004110097', x=12695036.076968, y=2481892.795233, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110096': SOURCE_INFO(source_identifier='1004110096', x=12695031.486679, y=2481891.8325025, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110068': SOURCE_INFO(source_identifier='1004110068', x=12695034.658626, y=2481899.4190306, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110069': SOURCE_INFO(source_identifier='1004110069', x=12695030.020056, y=2481898.4403828, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110072': SOURCE_INFO(source_identifier='1004110072', x=12695021.308935, y=2481901.1709051, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110071': SOURCE_INFO(source_identifier='1004110071', x=12695021.024823, y=2481901.3481831, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004120011': SOURCE_INFO(source_identifier='1004120011', x=12695033.906613, y=2481844.6431041, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004120010': SOURCE_INFO(source_identifier='1004120010', x=12695033.065672, y=2481848.3741248, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004120009': SOURCE_INFO(source_identifier='1004120009', x=12695032.267926, y=2481851.9899451, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004120008': SOURCE_INFO(source_identifier='1004120008', x=12695031.247131, y=2481856.8480235, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110091': SOURCE_INFO(source_identifier='1004110091', x=12695036.757559, y=2481843.471972, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110090': SOURCE_INFO(source_identifier='1004110090', x=12695036.004413, y=2481847.0404926, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110088': SOURCE_INFO(source_identifier='1004110088', x=12695035.126876, y=2481850.7649322, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110087': SOURCE_INFO(source_identifier='1004110087', x=12695035.069968, y=2481851.0357659, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110086': SOURCE_INFO(source_identifier='1004110086', x=12695034.352013, y=2481854.5429668, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110085': SOURCE_INFO(source_identifier='1004110085', x=12695034.353555, y=2481854.8067441, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110084': SOURCE_INFO(source_identifier='1004110084', x=12695033.946541, y=2481856.4726563, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110082': SOURCE_INFO(source_identifier='1004110082', x=12695033.171678, y=2481860.2506894, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110083': SOURCE_INFO(source_identifier='1004110083', x=12695033.243327, y=2481860.0000733, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110001': SOURCE_INFO(source_identifier='1004110001', x=12695059.066857, y=2481726.1755025, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110003': SOURCE_INFO(source_identifier='1004110003', x=12695057.463698, y=2481733.8823237, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110004': SOURCE_INFO(source_identifier='1004110004', x=12695056.593205, y=2481737.882095, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110005': SOURCE_INFO(source_identifier='1004110005', x=12695055.756256, y=2481741.8652503, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110006': SOURCE_INFO(source_identifier='1004110006', x=12695054.865505, y=2481745.9240693, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110007': SOURCE_INFO(source_identifier='1004110007', x=12695054.010892, y=2481749.6527706, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110008': SOURCE_INFO(source_identifier='1004110008', x=12695053.217037, y=2481753.5899297, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110010': SOURCE_INFO(source_identifier='1004110010', x=12695066.169266, y=2481727.749948, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004120001': SOURCE_INFO(source_identifier='1004120001', x=12695069.358391, y=2481731.0989677, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110012': SOURCE_INFO(source_identifier='1004110012', x=12695064.751388, y=2481737.3388347, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110013': SOURCE_INFO(source_identifier='1004110013', x=12695063.425516, y=2481741.0030315, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110015': SOURCE_INFO(source_identifier='1004110015', x=12695062.068692, y=2481749.0993417, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110014': SOURCE_INFO(source_identifier='1004110014', x=12695061.76733, y=2481748.6183573, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110002': SOURCE_INFO(source_identifier='1004110002', x=12695058.23067, y=2481730.1284834, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110009': SOURCE_INFO(source_identifier='1004110009', x=12695063.024806, y=2481727.0357097, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004120006': SOURCE_INFO(source_identifier='1004120006', x=12695049.523011, y=2481864.0401331, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110016': SOURCE_INFO(source_identifier='1004110016', x=12695066.196572, y=2481750.404379, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110017': SOURCE_INFO(source_identifier='1004110017', x=12695069.732676, y=2481751.1983358, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110112': SOURCE_INFO(source_identifier='1004110112', x=12695060.627382, y=2481755.7281587, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110022': SOURCE_INFO(source_identifier='1004110022', x=12695069.412334, y=2481760.8504771, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110024': SOURCE_INFO(source_identifier='1004110024', x=12695068.934805, y=2481762.9758112, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110025': SOURCE_INFO(source_identifier='1004110025', x=12695068.220921, y=2481766.554031, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110026': SOURCE_INFO(source_identifier='1004110026', x=12695068.170167, y=2481766.7983561, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110027': SOURCE_INFO(source_identifier='1004110027', x=12695067.75743, y=2481768.5791298, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110028': SOURCE_INFO(source_identifier='1004110028', x=12695067.025249, y=2481772.1540581, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110029': SOURCE_INFO(source_identifier='1004110029', x=12695066.941126, y=2481772.4087037, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110030': SOURCE_INFO(source_identifier='1004110030', x=12695066.532871, y=2481774.2262317, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110031': SOURCE_INFO(source_identifier='1004110031', x=12695065.831399, y=2481777.704865, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110032': SOURCE_INFO(source_identifier='1004110032', x=12695065.773976, y=2481777.8877738, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110033': SOURCE_INFO(source_identifier='1004110033', x=12695065.046478, y=2481781.3305774, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110034': SOURCE_INFO(source_identifier='1004110034', x=12695064.985499, y=2481781.5304135, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110035': SOURCE_INFO(source_identifier='1004110035', x=12695064.242133, y=2481785.068194, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110036': SOURCE_INFO(source_identifier='1004110036', x=12695064.188781, y=2481785.3221019, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110037': SOURCE_INFO(source_identifier='1004110037', x=12695063.429062, y=2481788.7736697, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110038': SOURCE_INFO(source_identifier='1004110038', x=12695063.346228, y=2481788.987142, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110039': SOURCE_INFO(source_identifier='1004110039', x=12695065.210783, y=2481793.450207, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110040': SOURCE_INFO(source_identifier='1004110040', x=12695064.01601, y=2481799.1144216, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110103': SOURCE_INFO(source_identifier='1004110103', x=12695055.82961, y=2481802.2464477, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110041': SOURCE_INFO(source_identifier='1004110041', x=12695063.074325, y=2481803.6571824, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110042': SOURCE_INFO(source_identifier='1004110042', x=12695062.205444, y=2481807.6115562, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110043': SOURCE_INFO(source_identifier='1004110043', x=12695061.316083, y=2481811.8441452, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110044': SOURCE_INFO(source_identifier='1004110044', x=12695060.423851, y=2481815.9220302, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110045': SOURCE_INFO(source_identifier='1004110045', x=12695059.566424, y=2481820.0126197, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110046': SOURCE_INFO(source_identifier='1004110046', x=12695058.672923, y=2481824.2237457, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110047': SOURCE_INFO(source_identifier='1004110047', x=12695057.802998, y=2481828.2010103, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110048': SOURCE_INFO(source_identifier='1004110048', x=12695056.974406, y=2481832.2245685, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110049': SOURCE_INFO(source_identifier='1004110049', x=12695056.023246, y=2481836.4621374, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110050': SOURCE_INFO(source_identifier='1004110050', x=12695055.173288, y=2481840.4146569, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110051': SOURCE_INFO(source_identifier='1004110051', x=12695054.299928, y=2481844.4563833, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110052': SOURCE_INFO(source_identifier='1004110052', x=12695053.352795, y=2481848.7831433, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110053': SOURCE_INFO(source_identifier='1004110053', x=12695052.527619, y=2481852.71023, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110054': SOURCE_INFO(source_identifier='1004110054', x=12695051.698034, y=2481856.8397405, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110058': SOURCE_INFO(source_identifier='1004110058', x=12695046.983039, y=2481865.4327483, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110061': SOURCE_INFO(source_identifier='1004110061', x=12695047.713706, y=2481875.1699809, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110062': SOURCE_INFO(source_identifier='1004110062', x=12695046.741392, y=2481879.7002257, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110063': SOURCE_INFO(source_identifier='1004110063', x=12695045.854727, y=2481883.7392155, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110064': SOURCE_INFO(source_identifier='1004110064', x=12695044.876291, y=2481888.2184552, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110067': SOURCE_INFO(source_identifier='1004110067', x=12695042.139679, y=2481901.1629418, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110065': SOURCE_INFO(source_identifier='1004110065', x=12695044.008528, y=2481892.540014, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110066': SOURCE_INFO(source_identifier='1004110066', x=12695043.104594, y=2481896.7515613, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110099': SOURCE_INFO(source_identifier='1004110099', x=12695037.90532, y=2481888.6809912, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110098': SOURCE_INFO(source_identifier='1004110098', x=12695037.151284, y=2481892.2695288, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110070': SOURCE_INFO(source_identifier='1004110070', x=12695018.329397, y=2481900.793235, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110073': SOURCE_INFO(source_identifier='1004110073', x=12695022.461701, y=2481896.9239165, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110074': SOURCE_INFO(source_identifier='1004110074', x=12695023.433747, y=2481892.5797302, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110075': SOURCE_INFO(source_identifier='1004110075', x=12695024.270763, y=2481888.6972211, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004120012': SOURCE_INFO(source_identifier='1004120012', x=12695035.33941, y=2481839.2717146, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110095': SOURCE_INFO(source_identifier='1004110095', x=12695038.338081, y=2481836.0647017, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110094': SOURCE_INFO(source_identifier='1004110094', x=12695037.572346, y=2481839.5281808, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110093': SOURCE_INFO(source_identifier='1004110093', x=12695037.57057, y=2481839.7123919, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110089': SOURCE_INFO(source_identifier='1004110089', x=12695035.994381, y=2481847.2639937, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110081': SOURCE_INFO(source_identifier='1004110081', x=12695029.617414, y=2481863.733395, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110080': SOURCE_INFO(source_identifier='1004110080', x=12695028.712127, y=2481868.0071392, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110079': SOURCE_INFO(source_identifier='1004110079', x=12695027.852362, y=2481871.8697719, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110078': SOURCE_INFO(source_identifier='1004110078', x=12695026.949739, y=2481876.109618, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110077': SOURCE_INFO(source_identifier='1004110077', x=12695026.02522, y=2481880.3429169, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110076': SOURCE_INFO(source_identifier='1004110076', x=12695025.115553, y=2481884.5458313, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110018': SOURCE_INFO(source_identifier='1004110018', x=12695071.264151, y=2481751.9797186, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110060': SOURCE_INFO(source_identifier='1004110060', x=12695046.216683, y=2481869.1632039, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '1004110092': SOURCE_INFO(source_identifier='1004110092', x=12695036.850255, y=2481843.3044216, z=1, type='non-lon-lat', activated=True, addition_info={}),
}

# [data_pool]
# maximum time range inside a current data pool
window_size_in_sec = 2.
# time gap between two updates in data pool(second)
sec_per_update = 1

# [TRACKING]
CAL_PERIOD_SEC = 1  # time gap between two calculations (second)

ALARM_PARAMS = {
    'ALARM_PIN': 17,

    # rule 1: alarm |= within(pos, ALARM_ZONE) and recent(vel_history) > ALARM_VELOCITY_THRESHOLD_HIGHER
    'ALARM_VELOCITY_THRESHOLD_HIGHER': 1.6,
    'ALARM_TOLERANCE_NUMBER': 5,

    # rule 2: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_HIGHER
    'STEP_ALARM_THRESHOLD_HIGHER': 12,

    # rule 3: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_LOWER and abs(vel)
    # >ALARM_VELOCITY_THRESHOLD_LOWER
    'ALARM_VELOCITY_THRESHOLD_LOWER': 1.5,
    'STEP_ALARM_THRESHOLD_LOWER': 10,

    'ALARM_ZONE': [polygon for polyid, polygon in MAPS.items()],  # it can't be empty list or None.
}

FILTER_MODULE_PARAMS = {
    'RSSI_FILTER_ALGO_FILE': "avg_filter.py",
    'RSSI_THRESHOLD': -80,
}

LOCALIZATION_MODULE_PARAMS = {
    'LOCALIZATION_ALGO_FILE': "new_localization_pf.cpython-37m-arm-linux-gnueabihf.so",
    'num_of_particles': 500,
    'max_vel': 2.5,  # maximum speed of human, unit in m/s
    'obs_model_mean': [-76.0656, 2.8871],  # mean and variance of P(rssi, distance)
    'obs_model_var': [[53.171, -5.218], [-5.218, 2.912]],
    'loss_model_range': [-120, -110],
    'weight_observe': 0.8,  # mean and variance of P(rssi, distance)
    'weight_loss': 1 - 0.8,
    'grid_size': 0.5,
}

VELOCITY_MODULE_PARAMS = {
    'VELOCITY_ALGO_FILE': "analyse_velocity.py",
    'VELOCITY_LEAST_SQUARE_WINDOW': 6,  # maximum num of position used to calculate velocity via least square
    'LOCALIZATION_CAL_PERIOD': CAL_PERIOD_SEC,
}

if __name__ == "__main__":
    # test for configs
    pass
