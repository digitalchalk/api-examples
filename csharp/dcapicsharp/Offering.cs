using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace dcapicsharp
{
    public class Offering
    {
        public String id {get; set;}
        public String title { get; set; }
        public String createdDate { get; set; }
        public String beginDate { get; set; }
        public String endDate { get; set; }
        public String registrationBeginDate { get; set; }
        public String registrationEndDate { get; set; }
        public String catalogDescrpition { get; set; }
        public String dashboardDescription { get; set; }
        public String deliveryDescription { get; set; }
        public double price { get; set; }
        public List<String> offeringCategoryIds { get; set; }
        public List<String> tags { get; set; }
        public int orderIndex { get; set; }


    }
}
