'use client';

import { useQuery } from '@tanstack/react-query';
import { apiClient } from '@/services/apiClient';
import { Loader2, CheckCircle2, Clock, XCircle } from 'lucide-react';

interface BloodRequest {
  id: number;
  receiver_name: string;
  group_name: string;
  units_requested: number;
  status: 'PENDING' | 'APPROVED' | 'REJECTED' | 'DELIVERED';
  requested_at: string;
}

export default function HospitalRequestsPage() {
  const { data, isLoading, refetch } = useQuery({
    queryKey: ['hospital-requests'],
    queryFn: async () => {
      const response = await apiClient.get('/hospital/requests');
      return response.data.data as BloodRequest[];
    }
  });

  const handleDeliver = async (requestId: number) => {
    try {
      await apiClient.put(`/hospital/requests/${requestId}/deliver`);
      refetch();
    } catch (error) {
      alert('Failed to mark request as delivered.');
    }
  };

  const getStatusIcon = (status: string) => {
    switch (status) {
      case 'PENDING': return <Clock className="h-5 w-5 text-yellow-500" />;
      case 'APPROVED': return <CheckCircle2 className="h-5 w-5 text-blue-500" />;
      case 'DELIVERED': return <CheckCircle2 className="h-5 w-5 text-green-500" />;
      case 'REJECTED': return <XCircle className="h-5 w-5 text-red-500" />;
      default: return null;
    }
  };

  const getStatusBadge = (status: string) => {
    switch (status) {
      case 'PENDING':
        return <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>;
      case 'APPROVED':
        return <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Approved</span>;
      case 'DELIVERED':
        return <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Delivered</span>;
      case 'REJECTED':
        return <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>;
      default:
        return null;
    }
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900">Blood Requests</h1>
        <p className="mt-2 text-sm text-gray-500">View requests made by receivers for your blood inventory.</p>
      </div>

      {isLoading ? (
        <div className="flex justify-center py-12">
          <Loader2 className="h-8 w-8 animate-spin text-red-600" />
        </div>
      ) : (
        <div className="bg-white shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg overflow-hidden">
          <table className="min-w-full divide-y divide-gray-300">
            <thead className="bg-gray-50">
              <tr>
                <th scope="col" className="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Receiver Name</th>
                <th scope="col" className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Requested Blood Group</th>
                <th scope="col" className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Units Needed</th>
                <th scope="col" className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Requested At</th>
                <th scope="col" className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                <th scope="col" className="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-200 bg-white">
              {data?.length === 0 ? (
                <tr>
                  <td colSpan={4} className="py-8 text-center text-sm text-gray-500">No requests found.</td>
                </tr>
              ) : (
                data?.map((req) => (
                  <tr key={req.id}>
                    <td className="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                      {req.receiver_name}
                    </td>
                    <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                      <div className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {req.group_name}
                      </div>
                    </td>
                    <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-semibold">
                      {req.units_requested}
                    </td>
                    <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                      {new Date(req.requested_at).toLocaleString()}
                    </td>
                    <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500 flex items-center gap-2 mt-1.5">
                      {getStatusIcon(req.status)}
                      {getStatusBadge(req.status)}
                    </td>
                    <td className="whitespace-nowrap px-3 py-4 text-sm text-right">
                      {(req.status === 'PENDING' || req.status === 'APPROVED') && (
                        <button
                          onClick={() => handleDeliver(req.id)}
                          className="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                          Mark Delivered
                        </button>
                      )}
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}
