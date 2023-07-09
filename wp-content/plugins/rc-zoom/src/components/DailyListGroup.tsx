import { useState, useEffect } from '@wordpress/element';
import availabilityService from "../services/availability-service";
import {CanceledError} from "../services/api-client";
import HourlyListGroup from "./HourlyListGroup";
import TimeSelect from "./TimeSelect";

interface Props {
    heading: string;
    days: string[];
}
export interface Availability {
    id: string;
    day_of_week: string;
    start_date: string
    end_date: string
    start_time: string
    end_time: string
}

let listItemStyles = "w-full border-b-2 border-neutral-100 border-opacity-100 py-2 px-1 cursor-pointer dark:border-opacity-50";
const desiredOrder = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
const week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];


export default function DailyListGroup({days, heading}: Props) {



    const [sunday, setSunday] = useState([]);
    const [monday, setMonday] = useState([]);
    const [tuesday, setTuesday] = useState([]);
    const [wednesday, setWednesday] = useState([]);
    const [thursday, setThursday] = useState([]);
    const [friday, setFriday] = useState([]);
    const [saturday, setSaturday] = useState([]);

    const [showAll, setShowAll] = useState(false);


    const [availability, setAvailability] = useState({});
    const [stateChanged, setStateChanged] = useState(false);
    const [error, setError] = useState("");
    const [isLoading, setLoading] = useState(false);


    // ... weekdays' state mappings
    const weekdays = {
        Sunday: [sunday, setSunday],
        Monday: [monday, setMonday],
        Tuesday: [tuesday, setTuesday],
        Wednesday: [wednesday, setWednesday],
        Thursday: [thursday, setThursday],
        Friday: [friday, setFriday],
        Saturday: [saturday, setSaturday],
    };



    useEffect(() => {
        setLoading(true);
        const { request, cancel } = availabilityService.get<Availability>(1);
        request
            .then((res) => {

                setLoading(false);

                // Grouping the objects by weekday
                const groupedData = res.data.data.reduce((acc, obj) => {
                    const { day_of_week } = obj;
                    if (!acc[day_of_week]) {
                        acc[day_of_week] = [];
                    }
                    acc[day_of_week].push(obj);
                    return acc;
                }, {});


                // Add days that are not set in the database
                days.forEach(day=> {

                    if (!groupedData[day]){
                       groupedData[day] = []
                    }
                })

                setSunday(groupedData['Sunday']);
                setMonday(groupedData['Monday']);
                setTuesday(groupedData['Tuesday']);
                setWednesday(groupedData['Wednesday']);
                setThursday(groupedData['Thursday']);
                setFriday(groupedData['Friday']);
                setSaturday(groupedData['Saturday']);


                const sortedObject = {};
                desiredOrder.forEach(day => {
                    if (groupedData.hasOwnProperty(day)) {
                        sortedObject[day] = groupedData[day];
                    }
                });

                setAvailability(sortedObject);



            })
            .catch((err) => {
                if (err instanceof CanceledError) return;
                setError(err.message);
                setLoading(false);
            });

        return () => cancel();
    }, []);

    const [selectedItem, setSelectedItem] = useState('')
    const handleClick = (item) => {
        setSelectedItem(item)
        console.log(item)
    }

    const handleOnDelete = (item) =>{
        // Set state has changed
        setStateChanged(true);

        // Filter out the selected time slot
        let filteredAvailability = weekdays[item.day_of_week][0].filter(slot=> slot.id != item.id);

        // Update the state
        weekdays[item.day_of_week][1](filteredAvailability)

    }
    const filterAvailability = (weekday,timeslot) =>{
        let filteredData = availability[weekday].filter(time => time != timeslot)
        return filteredData;
    }

    const onUpdateAvailability = (e,selectedAvailability,time) => {

        setStateChanged(true);

        let availability = weekdays[selectedAvailability.day_of_week][0]

        let updatedAvailability = availability.map(slot=> {
            if (slot.id != selectedAvailability.id)
                return slot;

            selectedAvailability[time] = e.target.value
            return selectedAvailability;
        });

        weekdays[selectedAvailability.day_of_week][1](updatedAvailability)
    }

    return (
        <>

            <h1 className={"text-lg"}>{heading} {stateChanged && <span className={"float-right hover:text-green-400 cursor-pointer"}>
                save <i className="fa-solid fa-save  "></i>
            </span>}
            </h1>
            <button onClick={()=>setShowAll(!showAll)} className="bg-transparent hover:bg-violet-500 text-violet-700 font-semibold hover:text-white py-0 px-4 border border-violet-500 hover:border-transparent rounded">
                {showAll ? 'collapse' : 'expand'}
            </button>
            {isLoading ? 'Loading...' :
                <>
                    {week.map(day =>
                        (<div onClick={()=>handleClick(day)}

                              // className={selectedItem === day ? 'bg-violet-200 ' + listItemStyles: 'hover:bg-slate-200 ' + listItemStyles }
                              className={selectedItem === day ? 'bg-violet-200 ' + listItemStyles : listItemStyles }
                        >
                            <HourlyListGroup
                                showAll={showAll}
                                isSelected={selectedItem === day}
                                heading={day} timeSlots={weekdays[day][0]}
                                onDelete={(slot)=>handleOnDelete(slot)}
                                onUpdateAvailability={(e, slot, time)=>onUpdateAvailability(e, slot,time)}
                                key={day}/>
                            <h1 >{(selectedItem !== day && !showAll) && `${day} slots: ${weekdays[day][0].length}` }</h1>
                        </div>)
                    )}
                </>
            }
        </>

    );
}