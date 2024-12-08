# on-board-program-from-pi version of different sites
from collections import namedtuple
from enum import Enum
from config.common import SOURCE_INFO
import os

MAPS = {
11:[[12695319.224113693,2480168.645699297,1],[12695313.838169776,2480238.7373620914,1],[12695315.239397204,2480238.8570082025,1],[12695320.626937233,2480168.702093603,1]],
12:[[12695317.80332229,2480254.800168429,1],[12695309.755863078,2480254.1521512643,1],[12695309.493901081,2480257.982421428,1],[12695317.54327666,2480258.6050841515,1]],
13:[[12695301.042994648,2480136.946285837,1],[12695311.13967447,2480137.664098457,1],[12695310.823728,2480141.8442891627,1],[12695300.743106158,2480140.996527122,1]],
14:[[12695309.514254682,2480232.6726839147,1],[12695314.330384335,2480232.984261197,1],[12695312.220885329,2480257.170680851,1],[12695307.870131357,2480254.7146363766,1]],
15:[[12695306.469918672,2480139.3811994684,1],[12695319.465810327,2480140.327482955,1],[12695318.95997335,2480147.289881035,1],[12695305.99532267,2480146.1871676003,1]],
16:[[12695302.907411529,2480243.569277893,1],[12695308.901151182,2480244.0655160286,1],[12695308.381695373,2480250.938230872,1],[12695302.493090674,2480250.483319401,1]],
17:[[12695301.666772513,2480216.590345096,1],[12695305.187391892,2480216.858809824,1],[12695302.280110853,2480255.0338031496,1],[12695298.986534454,2480252.233433639,1]],
18:[[12695303.50970024,2480237.8120026095,1],[12695309.410734495,2480238.2331142444,1],[12695308.969472,2480242.945902861,1],[12695303.092558611,2480242.487004683,1]],
19:[[12695304.06696446,2480242.4243396674,1],[12695307.860601284,2480242.7059695157,1],[12695306.975842943,2480253.9192084773,1],[12695303.211441131,2480253.5373700773,1]],
110:[[12695302.393983321,2480251.5706258425,1],[12695303.747524427,2480251.710055891,1],[12695303.437663041,2480255.562479453,1],[12695302.176315486,2480254.9449552028,1]],
111:[[12695306.959370391,2480251.918586453,1],[12695308.86696535,2480252.052980422,1],[12695308.608083645,2480255.015098595,1],[12695306.800204968,2480253.898783908,1]],
112:[[12695305.347125357,2480237.34758933,1],[12695309.342470147,2480237.6406417857,1],[12695309.285257006,2480238.3976049875,1],[12695305.292512847,2480238.0701450203,1]],
113:[[12695317.469964696,2480168.460907217,1],[12695319.565717602,2480168.621493762,1],[12695318.85920622,2480178.3266585935,1],[12695316.632750671,2480178.107611961,1]],
114:[[12695315.983766101,2480146.748731428,1],[12695319.317144604,2480147.0085334615,1],[12695317.36059314,2480175.0995911174,1],[12695313.857436236,2480174.8038036916,1]],
115:[[12695305.87350859,2480147.35655936,1],[12695316.51904852,2480148.1779971095,1],[12695316.134291096,2480152.5535054775,1],[12695305.459473304,2480152.366663425,1]],
116:[[12695312.160851019,2480136.9631539485,1],[12695315.893991742,2480137.2724692994,1],[12695314.681518216,2480152.513376358,1],[12695310.981725289,2480152.4582567695,1]],
117:[[12695315.790869134,2480137.725299312,1],[12695319.813746968,2480138.0083741797,1],[12695319.74047401,2480139.3040965856,1],[12695315.671947867,2480138.9724382134,1]],
118:[[12695316.588515589,2480138.9176599635,1],[12695316.468792913,2480140.5016704416,1],[12695319.406753626,2480140.783002092,1],[12695319.714616494,2480139.3199355435,1]],
119:[[12695307.278443778,2480146.291042197,1],[12695310.608970622,2480146.4078199985,1],[12695308.504299074,2480173.2091056723,1],[12695305.138300003,2480172.9623486735,1]],
120:[[12695302.5698036,2480169.0931115616,1],[12695305.507201908,2480169.2096926896,1],[12695302.732564362,2480204.447495545,1],[12695299.97569652,2480204.1820510523,1]],
121:[[12695299.51219214,2480208.8387996685,1],[12695303.302861553,2480209.655645629,1],[12695302.28948603,2480223.5440888708,1],[12695298.3834899,2480223.2913921904,1]],
122:[[12695303.030140258,2480210.487095945,1],[12695304.28612369,2480211.699133498,1],[12695303.953773096,2480216.9256202006,1],[12695302.548184019,2480216.8636805476,1]],
123:[[12695300.119203288,2480200.096521485,1],[12695299.384927226,2480210.058692372,1],[12695301.5778481,2480210.2178503517,1],[12695302.140867429,2480200.296463883,1]],
124:[[12695303.07310794,2480199.432687231,1],[12695303.891716648,2480200.4483592142,1],[12695303.718611144,2480204.0193452984,1],[12695302.602945074,2480204.3725520438,1]],
125:[[12695305.122308513,2480172.7941718437,1],[12695307.275720537,2480172.9735830645,1],[12695306.774795085,2480181.321387521,1],[12695304.09812443,2480185.1979867877,1]],
126:[[12695305.972035903,2480146.084818223,1],[12695307.285868065,2480146.1982690864,1],[12695306.75604921,2480154.109488073,1],[12695305.307928883,2480153.9700617376,1]],
127:[[12695305.296628281,2480136.442165062,1],[12695312.289637288,2480136.9917149898,1],[12695312.197825426,2480138.6694948,1],[12695305.17485969,2480138.0532485265,1]],
128:[[12695312.377171332,2480251.550141483,1],[12695314.175967421,2480251.689763087,1],[12695313.957379969,2480254.581805764,1],[12695312.030048542,2480254.4803676344,1]],
129:[[12695304.637853812,2480141.296254376,1],[12695304.475414224,2480143.13125258,1],[12695306.31371554,2480143.316092329,1],[12695306.43715461,2480141.3687193515,1]],
130:[[12695319.307151072,2480137.890023799,1],[12695320.40042208,2480138.0554790716,1],[12695320.006145688,2480143.637760663,1],[12695318.920422146,2480143.54857465,1]],
}

SOURCE_INFO_DICT = {
'1009110085': SOURCE_INFO(source_identifier='1009110085', x=12695306.176754, y=2480161.0428103, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110102': SOURCE_INFO(source_identifier='1009110102', x=12695301.293075, y=2480223.720503, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110049': SOURCE_INFO(source_identifier='1009110049', x=12695307.219875, y=2480251.3972131, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110051': SOURCE_INFO(source_identifier='1009110051', x=12695303.94498, y=2480243.1049181, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110053': SOURCE_INFO(source_identifier='1009110053', x=12695303.278157, y=2480243.0272026, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110055': SOURCE_INFO(source_identifier='1009110055', x=12695303.325949, y=2480251.1229688, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110057': SOURCE_INFO(source_identifier='1009110057', x=12695302.677722, y=2480251.0464559, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110006': SOURCE_INFO(source_identifier='1009110006', x=12695319.157131, y=2480150.3777624, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110007': SOURCE_INFO(source_identifier='1009110007', x=12695318.741426, y=2480156.125035, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110008': SOURCE_INFO(source_identifier='1009110008', x=12695318.740518, y=2480156.3842952, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110009': SOURCE_INFO(source_identifier='1009110009', x=12695318.295894, y=2480162.1267251, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110010': SOURCE_INFO(source_identifier='1009110010', x=12695318.27769, y=2480162.367578, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110013': SOURCE_INFO(source_identifier='1009110013', x=12695314.022553, y=2480171.4980098, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110014': SOURCE_INFO(source_identifier='1009110014', x=12695320.481709, y=2480172.4689434, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110015': SOURCE_INFO(source_identifier='1009110015', x=12695320.182854, y=2480176.3731222, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110016': SOURCE_INFO(source_identifier='1009110016', x=12695319.890777, y=2480180.2865413, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110017': SOURCE_INFO(source_identifier='1009110017', x=12695319.582377, y=2480184.1958452, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110018': SOURCE_INFO(source_identifier='1009110018', x=12695319.28717, y=2480188.1016368, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110019': SOURCE_INFO(source_identifier='1009110019', x=12695319.007784, y=2480192.0215447, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110061': SOURCE_INFO(source_identifier='1009110061', x=12695315.775875, y=2480139.5152934, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110063': SOURCE_INFO(source_identifier='1009110063', x=12695315.857081, y=2480147.5625678, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110065': SOURCE_INFO(source_identifier='1009110065', x=12695315.207996, y=2480147.5231989, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110067': SOURCE_INFO(source_identifier='1009110067', x=12695311.866522, y=2480139.2246869, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110069': SOURCE_INFO(source_identifier='1009110069', x=12695311.218003, y=2480139.1603423, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110073': SOURCE_INFO(source_identifier='1009110073', x=12695310.611782, y=2480147.1810699, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110075': SOURCE_INFO(source_identifier='1009110075', x=12695304.280079, y=2480137.0661948, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110076': SOURCE_INFO(source_identifier='1009110076', x=12695301.146166, y=2480136.8644695, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110082': SOURCE_INFO(source_identifier='1009110082', x=12695306.692783, y=2480154.7871213, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110078': SOURCE_INFO(source_identifier='1009110078', x=12695303.960206, y=2480141.2983339, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110083': SOURCE_INFO(source_identifier='1009110083', x=12695306.693175, y=2480155.0291776, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110084': SOURCE_INFO(source_identifier='1009110084', x=12695306.177664, y=2480160.7835505, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110086': SOURCE_INFO(source_identifier='1009110086', x=12695305.705266, y=2480166.7864701, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110087': SOURCE_INFO(source_identifier='1009110087', x=12695305.687062, y=2480167.0273232, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110088': SOURCE_INFO(source_identifier='1009110088', x=12695302.209226, y=2480171.617735, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110089': SOURCE_INFO(source_identifier='1009110089', x=12695301.888962, y=2480175.6078087, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110090': SOURCE_INFO(source_identifier='1009110090', x=12695301.572583, y=2480179.5928797, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110091': SOURCE_INFO(source_identifier='1009110091', x=12695301.289509, y=2480183.5853574, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110092': SOURCE_INFO(source_identifier='1009110092', x=12695300.980162, y=2480187.5761302, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110094': SOURCE_INFO(source_identifier='1009110094', x=12695300.365414, y=2480195.5969487, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110095': SOURCE_INFO(source_identifier='1009110095', x=12695300.043837, y=2480199.5339644, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110096': SOURCE_INFO(source_identifier='1009110096', x=12695299.764545, y=2480203.5060464, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110097': SOURCE_INFO(source_identifier='1009110097', x=12695299.461577, y=2480207.5145186, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110098': SOURCE_INFO(source_identifier='1009110098', x=12695299.134174, y=2480211.5172276, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110099': SOURCE_INFO(source_identifier='1009110099', x=12695298.861954, y=2480215.4774724, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110100': SOURCE_INFO(source_identifier='1009110100', x=12695298.519054, y=2480219.4601161, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110101': SOURCE_INFO(source_identifier='1009110101', x=12695305.124318, y=2480219.0558531, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110103': SOURCE_INFO(source_identifier='1009110103', x=12695301.293467, y=2480223.9625579, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110104': SOURCE_INFO(source_identifier='1009110104', x=12695300.936921, y=2480227.6909535, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110105': SOURCE_INFO(source_identifier='1009110105', x=12695300.973203, y=2480227.9526182, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110106': SOURCE_INFO(source_identifier='1009110106', x=12695300.511073, y=2480233.6865092, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110107': SOURCE_INFO(source_identifier='1009110107', x=12695300.494169, y=2480233.9101575, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110108': SOURCE_INFO(source_identifier='1009110108', x=12695300.036802, y=2480239.6923784, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110109': SOURCE_INFO(source_identifier='1009110109', x=12695300.037192, y=2480239.9344332, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110111': SOURCE_INFO(source_identifier='1009110111', x=12695299.585172, y=2480245.9149608, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110021': SOURCE_INFO(source_identifier='1009110021', x=12695318.38974, y=2480199.8579511, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110022': SOURCE_INFO(source_identifier='1009110022', x=12695318.092783, y=2480203.7471132, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110023': SOURCE_INFO(source_identifier='1009110023', x=12695317.797616, y=2480207.652363, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110024': SOURCE_INFO(source_identifier='1009110024', x=12695317.482021, y=2480211.5806561, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110025': SOURCE_INFO(source_identifier='1009110025', x=12695317.20545, y=2480215.4871069, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110026': SOURCE_INFO(source_identifier='1009110026', x=12695316.90937, y=2480219.404438, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110027': SOURCE_INFO(source_identifier='1009110027', x=12695316.594249, y=2480223.3354878, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110028': SOURCE_INFO(source_identifier='1009110028', x=12695316.283087, y=2480227.2051223, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110030': SOURCE_INFO(source_identifier='1009110030', x=12695315.702035, y=2480235.0294941, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110032': SOURCE_INFO(source_identifier='1009110032', x=12695315.201258, y=2480241.0489718, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110033': SOURCE_INFO(source_identifier='1009110033', x=12695314.944345, y=2480244.9750219, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110034': SOURCE_INFO(source_identifier='1009110034', x=12695314.386059, y=2480251.7600888, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110035': SOURCE_INFO(source_identifier='1009110035', x=12695314.223553, y=2480254.236407, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110036': SOURCE_INFO(source_identifier='1009110036', x=12695314.915536, y=2480254.3558512, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110037': SOURCE_INFO(source_identifier='1009110037', x=12695317.433544, y=2480254.5597407, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110039': SOURCE_INFO(source_identifier='1009110039', x=12695314.557172, y=2480258.6027559, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110040': SOURCE_INFO(source_identifier='1009110040', x=12695309.130924, y=2480235.4756521, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110041': SOURCE_INFO(source_identifier='1009110041', x=12695304.754404, y=2480237.8453484, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110043': SOURCE_INFO(source_identifier='1009110043', x=12695308.489857, y=2480243.4734991, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110045': SOURCE_INFO(source_identifier='1009110045', x=12695307.861151, y=2480243.3985257, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110047': SOURCE_INFO(source_identifier='1009110047', x=12695307.92073, y=2480251.4547755, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110002': SOURCE_INFO(source_identifier='1009110002', x=12695319.438153, y=2480143.950979, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110003': SOURCE_INFO(source_identifier='1009110003', x=12695319.254029, y=2480145.8926036, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110004': SOURCE_INFO(source_identifier='1009110004', x=12695318.98916, y=2480149.6442365, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110001': SOURCE_INFO(source_identifier='1009110001', x=12695319.440124, y=2480143.6867083, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110005': SOURCE_INFO(source_identifier='1009110005', x=12695318.931164, y=2480149.9170916, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110011': SOURCE_INFO(source_identifier='1009110011', x=12695317.835331, y=2480168.1089687, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110012': SOURCE_INFO(source_identifier='1009110012', x=12695317.795473, y=2480168.3624618, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110020': SOURCE_INFO(source_identifier='1009110020', x=12695318.686207, y=2480195.9354956, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110029': SOURCE_INFO(source_identifier='1009110029', x=12695316.00576, y=2480231.1407557, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110031': SOURCE_INFO(source_identifier='1009110031', x=12695315.439049, y=2480238.6390369, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110038': SOURCE_INFO(source_identifier='1009110038', x=12695317.090055, y=2480258.8078624, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110042': SOURCE_INFO(source_identifier='1009110042', x=12695308.231972, y=2480242.941146, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110044': SOURCE_INFO(source_identifier='1009110044', x=12695308.164099, y=2480243.8965099, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110046': SOURCE_INFO(source_identifier='1009110046', x=12695307.609011, y=2480250.9360817, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110048': SOURCE_INFO(source_identifier='1009110048', x=12695307.573217, y=2480251.9136407, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110050': SOURCE_INFO(source_identifier='1009110050', x=12695303.642884, y=2480242.5923945, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110052': SOURCE_INFO(source_identifier='1009110052', x=12695303.592362, y=2480243.5508649, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110054': SOURCE_INFO(source_identifier='1009110054', x=12695303.018623, y=2480250.6045335, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110056': SOURCE_INFO(source_identifier='1009110056', x=12695302.949539, y=2480251.5280754, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110058': SOURCE_INFO(source_identifier='1009110058', x=12695316.167121, y=2480139.0472101, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110059': SOURCE_INFO(source_identifier='1009110059', x=12695316.423728, y=2480139.6011513, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110060': SOURCE_INFO(source_identifier='1009110060', x=12695316.114199, y=2480139.9946234, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110062': SOURCE_INFO(source_identifier='1009110062', x=12695315.561184, y=2480147.0641868, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110064': SOURCE_INFO(source_identifier='1009110064', x=12695315.471072, y=2480148.0091932, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110066': SOURCE_INFO(source_identifier='1009110066', x=12695311.604506, y=2480138.7320235, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110068': SOURCE_INFO(source_identifier='1009110068', x=12695311.534289, y=2480139.6610296, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110081': SOURCE_INFO(source_identifier='1009110081', x=12695303.588414, y=2480153.5222656, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110070': SOURCE_INFO(source_identifier='1009110070', x=12695311.010587, y=2480146.700635, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110071': SOURCE_INFO(source_identifier='1009110071', x=12695311.319987, y=2480147.197412, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110072': SOURCE_INFO(source_identifier='1009110072', x=12695310.90188, y=2480147.6444384, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110074': SOURCE_INFO(source_identifier='1009110074', x=12695310.684338, y=2480152.8151784, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110077': SOURCE_INFO(source_identifier='1009110077', x=12695300.802566, y=2480141.0461855, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110079': SOURCE_INFO(source_identifier='1009110079', x=12695304.408849, y=2480142.6673133, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110080': SOURCE_INFO(source_identifier='1009110080', x=12695303.86468, y=2480149.8670427, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110093': SOURCE_INFO(source_identifier='1009110093', x=12695300.679793, y=2480191.550199, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009120001': SOURCE_INFO(source_identifier='1009120001', x=12695321.018268, y=2480147.0898551, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009120002': SOURCE_INFO(source_identifier='1009120002', x=12695321.008498, y=2480156.5382785, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009120003': SOURCE_INFO(source_identifier='1009120003', x=12695319.846846, y=2480165.6949559, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009120004': SOURCE_INFO(source_identifier='1009120004', x=12695304.30503, y=2480163.7662974, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009120005': SOURCE_INFO(source_identifier='1009120005', x=12695298.977733, y=2480225.6921503, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009120006': SOURCE_INFO(source_identifier='1009120006', x=12695298.618385, y=2480230.8690987, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009120007': SOURCE_INFO(source_identifier='1009120007', x=12695298.203388, y=2480236.628175, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009120008': SOURCE_INFO(source_identifier='1009120008', x=12695297.573349, y=2480242.8512837, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1009110110': SOURCE_INFO(source_identifier='1009110110', x=12695299.584782, y=2480245.672906, z=1, type='non-lon-lat', activated=True, addition_info={}),
}
# [data_pool]
# maximum time range inside a current data pool
window_size_in_sec = 2.
# time gap between two updates in data pool(second)
sec_per_update = 1

# [TRACKING]
CAL_PERIOD_SEC = 1.5  # time gap between two calculations (second)

ALARM_PARAMS = {
	'ALARM_PIN': 17,

	# rule 1: alarm |= within(pos, ALARM_ZONE) and recent(vel_history) > ALARM_VELOCITY_THRESHOLD_HIGHER
	'ALARM_VELOCITY_THRESHOLD_HIGHER': 1.5,
	'ALARM_TOLERANCE_NUMBER': 3,

	# rule 2: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_HIGHER
	'STEP_ALARM_THRESHOLD_HIGHER': 10,

	# rule 3: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_LOWER and abs(vel) >ALARM_VELOCITY_THRESHOLD_LOWER
	'ALARM_VELOCITY_THRESHOLD_LOWER': 1.4,
	'STEP_ALARM_THRESHOLD_LOWER': 10,

	'ALARM_ZONE': [polygon for polyid, polygon in MAPS.items()],  # it can't be empty list or None.
}

FILTER_MODULE_PARAMS = {
	'RSSI_FILTER_ALGO_FILE': "avg_filter.py",
	'RSSI_THRESHOLD': -80,
}

LOCALIZATION_MODULE_PARAMS = {
	'LOCALIZATION_ALGO_FILE': "new_localization_pf.cpython-37m-arm-linux-gnueabihf.so",
	'num_of_particles': 800,
	'max_vel': 2.,  # maximum speed of human, unit in m/s
	'obs_model_mean': [-75.0656, 2.8871],  # mean and variance of P(rssi, distance)
	'obs_model_var': [[53.171, -5.218], [-5.218, 2.912]],
	'loss_model_range': [-120, -110],
	'weight_observe': 0.8,  # mean and variance of P(rssi, distance)
	'weight_loss': 1 - 0.8,
	'grid_size': 0.5,
	'cal_fq': CAL_PERIOD_SEC,
}

VELOCITY_MODULE_PARAMS = {
	'VELOCITY_ALGO_FILE': "analyse_velocity.py",
	'VELOCITY_LEAST_SQUARE_WINDOW': 5,  # maximum num of position used to calculate velocity via least square
	'LOCALIZATION_CAL_PERIOD': CAL_PERIOD_SEC,
}
